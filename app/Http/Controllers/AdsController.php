<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Ad;
use App\Models\AdTrainingSpecialization;
use App\Models\Category;
use App\Models\City;
use App\Models\Industry;
use App\Models\Region;
use App\Models\Subcategory;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class AdsController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Ad::query()
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->whereNotNull('expires_at')
            ->where('published_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->with(['category', 'photos', 'user.firm', 'job', 'training', 'machines']);

        $selectedSort = (string) $request->query('sort', 'newest');
        $search = $request->query('q');
        $city = $request->query('city');
        $this->applySearch($query, $search);
        $this->applyCity($query, $city);
        $allowedSorts = ['newest', 'oldest'];
        if (! in_array($selectedSort, $allowedSorts, true)) {
            $selectedSort = 'newest';
        }

        // Featured first, then chosen sorting
        $query->orderBy('is_featured', 'desc');
        if ($selectedSort === 'oldest') {
            $query->orderBy('id', 'asc')->orderBy('price_from', 'asc');
        } else {
            $query->orderBy('id', 'desc')->orderBy('price_from', 'asc');
        }

        $ads = $query->paginate(12);

        $categories = Category::query()->orderBy('name')->get(['id', 'name', 'slug']);

        return view('sites/ads/index', [
            'ads' => $ads,
            'categories' => $categories,
            'selectedCategory' => null,
            'subcategories' => collect(),
            'selectedSubcategory' => null,
            'selectedSort' => $selectedSort,
            'search' => $search,
            'city' => $city,
        ]);
    }

    public function show(string $code, string $slug)
    {
        $ad = Ad::query()
            ->with(['category', 'photos', 'job.specializations', 'training', 'user.firm', 'machines'])
            ->where('status', 'active')
            ->where('code', $code)
            ->firstOrFail();

        $expected = Str::slug((string) $ad->title);
        if ($slug !== $expected) {
            return redirect()->route('advertisement.show', [$ad->code, $expected]);
        }

        $subcategory = null;
        $category = $ad->category;
        if ($category) {
            if ($category->slug === 'szkolenia' && $ad->training?->subcategory_id) {
                $subcategory = Subcategory::query()->find($ad->training->subcategory_id);
            } elseif ($category->slug === 'urzadzenia-i-sprzet' && $ad->machines?->subcategory_id) {
                $subcategory = Subcategory::query()->find($ad->machines->subcategory_id);
            } elseif ($category->slug === 'praca' && ($ad->job?->job_type)) {
                $subcategory = Subcategory::query()
                    ->where('category_id', $category->id)
                    ->where('slug', $ad->job->job_type)
                    ->first();
            }
        }

        $base = Ad::query()
            ->with(['category', 'photos', 'user.firm', 'job', 'training', 'machines'])
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->whereNotNull('expires_at')
            ->where('published_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->where('category_id', $ad->category_id)
            ->where('id', '!=', $ad->id);

        $applySubcategory = function ($q) use ($ad, $subcategory): void {
            if (! $subcategory) {
                return;
            }

            if ($ad->category?->slug === 'szkolenia') {
                $q->whereHas('training', fn ($qq) => $qq->where('subcategory_id', $subcategory->id));
            } elseif ($ad->category?->slug === 'urzadzenia-i-sprzet') {
                $q->whereHas('machines', fn ($qq) => $qq->where('subcategory_id', $subcategory->id));
            } elseif ($ad->category?->slug === 'praca') {
                $q->whereHas('job', fn ($qq) => $qq->where('job_type', $subcategory->slug));
            }
        };

        $query = (clone $base);
        $applySubcategory($query);

        if (in_array($ad->category?->slug, ['praca', 'szkolenia'], true)) {
            $cityName = $ad->as_company
                ? ($ad->company_city ?? null)
                : ($ad->location ?? $ad->person_city ?? null);
            $cityName = trim((string) ($cityName ?? ''));
            if ($cityName !== '') {
                $query->where(function ($qq) use ($cityName): void {
                    $name = $cityName;
                    $qq->where('location', 'like', "%$name%")
                        ->orWhere('person_city', 'like', "%$name%")
                        ->orWhere('company_city', 'like', "%$name%");
                });
            }
        }

        // Featured first, then newest
        $query->orderBy('is_featured', 'desc')->orderBy('id', 'desc');

        $limit = 3;
        $similarAds = $query->take($limit)->get();

        if ($similarAds->count() < $limit) {
            // Fallback: only category criterion (still exclude current ad), refill up to limit
            $fallback = (clone $base)
                ->orderBy('is_featured', 'desc')
                ->orderBy('id', 'desc')
                ->take($limit)
                ->get();

            $similarAds = $similarAds->merge($fallback)->unique('id')->take($limit)->values();
        }

        return view('sites/ads/show', [
            'ad' => $ad,
            'subcategory' => $subcategory,
            'similarAds' => $similarAds,
        ]);
    }
    


    public function byCategory(Request $request, Category $category)
    {
        $query = Ad::query()
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->whereNotNull('expires_at')
            ->where('published_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->where('category_id', $category->id)
            ->with(['category', 'photos', 'user.firm', 'job', 'training', 'machines']);

        $subcategories = Subcategory::query()
            ->where('category_id', $category->id)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'category_id']);

        // Apply extra filters for job / training category
        $industries = collect();
        $regions = collect();
        $selectedSpecs = [];
        $selectedForms = [];
        $selectedRegion = null;
        $trainingSpecs = collect();
        $selectedTrainingSpecs = [];
        $selectedCert = false;
        $selectedPriceFrom = null;
        $selectedPriceTo = null;
        $selectedDateStart = null;
        $selectedDateEnd = null;
        if ($category->slug === 'praca') {
            $industries = Industry::query()->orderBy('name')->get(['id', 'name']);
            $regions = Region::query()->orderBy('name')->get(['id', 'name', 'slug']);

            $selectedSpecs = array_values(array_unique(array_map('intval', (array) $request->query('spec', []))));
            if ($selectedSpecs) {
                $query->whereHas('job.specializations', function ($q) use ($selectedSpecs): void {
                    $q->whereIn('industries.id', $selectedSpecs);
                });
            }

            $selectedForms = array_values(array_filter((array) $request->query('form', []), fn ($v) => is_string($v) && $v !== ''));
            if ($selectedForms) {
                $query->whereHas('job', function ($q) use ($selectedForms): void {
                    $q->where(function ($qq) use ($selectedForms): void {
                        foreach ($selectedForms as $f) {
                            $qq->orWhereRaw('FIND_IN_SET(?, employment_form)', [$f]);
                        }
                    });
                });
            }

            $selectedRegion = $request->query('region');
            if (is_string($selectedRegion) && $selectedRegion !== '') {
                $region = Region::query()->where('slug', $selectedRegion)->first();
                if ($region) {
                    $name = $region->name;
                    $query->where(function ($q) use ($name): void {
                        $q->where('location', 'like', "%$name%")
                            ->orWhere('person_region', 'like', "%$name%")
                            ->orWhere('person_city', 'like', "%$name%")
                            ->orWhere('company_region', 'like', "%$name%")
                            ->orWhere('company_city', 'like', "%$name%");
                    });
                }
            }
        } elseif ($category->slug === 'szkolenia') {
            $trainingSpecs = AdTrainingSpecialization::query()->orderBy('name')->get(['id', 'name']);
            $regions = Region::query()->orderBy('name')->get(['id', 'name', 'slug']);

            $selectedTrainingSpecs = array_values(array_unique(array_map('intval', (array) $request->query('tspec', []))));
            if ($selectedTrainingSpecs) {
                $query->whereHas('training.specializations', function ($q) use ($selectedTrainingSpecs): void {
                    $q->whereIn('ad_training_specializations.id', $selectedTrainingSpecs);
                });
            }

            $selectedCert = (bool) $request->boolean('cert');
            if ($selectedCert) {
                $query->whereHas('training', fn ($q) => $q->where('has_certificate', true));
            }

            $selectedPriceFrom = $request->query('price_from');
            $selectedPriceTo = $request->query('price_to');
            if ($selectedPriceFrom !== null || $selectedPriceTo !== null) {
                $query->whereHas('training', function ($q) use ($selectedPriceFrom, $selectedPriceTo): void {
                    if ($selectedPriceFrom !== null && $selectedPriceFrom !== '') {
                        $q->whereRaw('COALESCE(promo_price, price) >= ?', [(float) $selectedPriceFrom]);
                    }
                    if ($selectedPriceTo !== null && $selectedPriceTo !== '') {
                        $q->whereRaw('COALESCE(promo_price, price) <= ?', [(float) $selectedPriceTo]);
                    }
                });
            }

            $selectedDateStart = $request->query('date_start');
            $selectedDateEnd = $request->query('date_end');
            if (($selectedDateStart && $selectedDateStart !== '') || ($selectedDateEnd && $selectedDateEnd !== '')) {
                $query->whereHas('training.dates', function ($q) use ($selectedDateStart, $selectedDateEnd): void {
                    if ($selectedDateStart && $selectedDateEnd) {
                        $q->whereBetween('start_date', [$selectedDateStart, $selectedDateEnd]);
                    } elseif ($selectedDateStart) {
                        $q->where('start_date', '>=', $selectedDateStart);
                    } elseif ($selectedDateEnd) {
                        $q->where('start_date', '<=', $selectedDateEnd);
                    }
                });
            }

            $selectedRegion = $request->query('region');
            if (is_string($selectedRegion) && $selectedRegion !== '') {
                $region = Region::query()->where('slug', $selectedRegion)->first();
                if ($region) {
                    $name = $region->name;
                    $query->where(function ($q) use ($name): void {
                        $q->where('location', 'like', "%$name%")
                            ->orWhere('person_region', 'like', "%$name%")
                            ->orWhere('person_city', 'like', "%$name%")
                            ->orWhere('company_region', 'like', "%$name%")
                            ->orWhere('company_city', 'like', "%$name%");
                    });
                }
            }
        }

        $selectedSort = (string) $request->query('sort', 'newest');
        $search = $request->query('q');
        $city = $request->query('city');
        $this->applySearch($query, $search);
        $this->applyCity($query, $city);
        $allowedSorts = ['newest', 'oldest'];
        if ($category->slug === 'urzadzenia-i-sprzet') {
            $allowedSorts = array_merge($allowedSorts, ['price_asc', 'price_desc']);
        }
        if (! in_array($selectedSort, $allowedSorts, true)) {
            $selectedSort = 'newest';
        }

        // Featured first, then chosen sorting
        $query->orderBy('is_featured', 'desc');
        if ($selectedSort === 'oldest') {
            $query->orderBy('id', 'asc')->orderBy('price_from', 'asc');
        } elseif ($selectedSort === 'price_asc' && $category->slug === 'urzadzenia-i-sprzet') {
            $query->orderBy('price_from', 'asc')->orderBy('id', 'desc');
        } elseif ($selectedSort === 'price_desc' && $category->slug === 'urzadzenia-i-sprzet') {
            $query->orderBy('price_from', 'desc')->orderBy('id', 'desc');
        } else {
            $query->orderBy('id', 'desc')->orderBy('price_from', 'asc');
        }

        $ads = $query->paginate(12);
        $categories = Category::query()->orderBy('name')->get(['id', 'name', 'slug']);

        return view('sites/ads/index', [
            'ads' => $ads,
            'categories' => $categories,
            'selectedCategory' => $category,
            'subcategories' => $subcategories,
            'selectedSubcategory' => null,
            'industries' => $industries,
            'regions' => $regions,
            'selectedSpecs' => $selectedSpecs,
            'selectedForms' => $selectedForms,
            'selectedRegion' => $selectedRegion,
            'trainingSpecs' => $trainingSpecs,
            'selectedTrainingSpecs' => $selectedTrainingSpecs,
            'selectedCert' => $selectedCert,
            'selectedPriceFrom' => $selectedPriceFrom,
            'selectedPriceTo' => $selectedPriceTo,
            'selectedDateStart' => $selectedDateStart,
            'selectedDateEnd' => $selectedDateEnd,
            'selectedSort' => $selectedSort,
            'search' => $search,
            'city' => $city,
        ]);
    }

    public function byCategorySubcategory(Request $request, Category $category, Subcategory $subcategory)
    {
        abort_unless($subcategory->category_id === $category->id, 404);

        $query = Ad::query()
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->whereNotNull('expires_at')
            ->where('published_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->where('category_id', $category->id)
            ->with(['category', 'photos', 'user.firm', 'job', 'training', 'machines']);

        if ($category->slug === 'szkolenia') {
            $query->whereHas('training', fn ($q) => $q->where('subcategory_id', $subcategory->id));
        } elseif ($category->slug === 'urzadzenia-i-sprzet') {
            $query->whereHas('machines', fn ($q) => $q->where('subcategory_id', $subcategory->id));
        } elseif ($category->slug === 'praca') {
            $query->whereHas('job', fn ($q) => $q->where('job_type', $subcategory->slug));
        }

        $selectedSort = (string) $request->query('sort', 'newest');
        $search = $request->query('q');
        $city = $request->query('city');
        $this->applySearch($query, $search);
        $this->applyCity($query, $city);
        $allowedSorts = ['newest', 'oldest'];
        if ($category->slug === 'urzadzenia-i-sprzet') {
            $allowedSorts = array_merge($allowedSorts, ['price_asc', 'price_desc']);
        }
        if (! in_array($selectedSort, $allowedSorts, true)) {
            $selectedSort = 'newest';
        }

        $query->orderBy('is_featured', 'desc');
        if ($selectedSort === 'oldest') {
            $query->orderBy('id', 'asc')->orderBy('price_from', 'asc');
        } elseif ($selectedSort === 'price_asc' && $category->slug === 'urzadzenia-i-sprzet') {
            $query->orderBy('price_from', 'asc')->orderBy('id', 'desc');
        } elseif ($selectedSort === 'price_desc' && $category->slug === 'urzadzenia-i-sprzet') {
            $query->orderBy('price_from', 'desc')->orderBy('id', 'desc');
        } else {
            $query->orderBy('id', 'desc')->orderBy('price_from', 'asc');
        }

        $ads = $query->paginate(12);
        $categories = Category::query()->orderBy('name')->get(['id', 'name', 'slug']);
        $subcategories = Subcategory::query()
            ->where('category_id', $category->id)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'category_id']);

        // Extra filters for job / training / devices category
        $industries = collect();
        $regions = collect();
        $selectedSpecs = [];
        $selectedForms = [];
        $selectedRegion = null;
        $trainingSpecs = collect();
        $selectedTrainingSpecs = [];
        $selectedCert = false;
        $selectedPriceFrom = null;
        $selectedPriceTo = null;
        $selectedDateStart = null;
        $selectedDateEnd = null;
        if ($category->slug === 'praca') {
            $industries = Industry::query()->orderBy('name')->get(['id', 'name']);
            $regions = Region::query()->orderBy('name')->get(['id', 'name', 'slug']);

            $selectedSpecs = array_values(array_unique(array_map('intval', (array) $request->query('spec', []))));
            if ($selectedSpecs) {
                $query->whereHas('job.specializations', function ($q) use ($selectedSpecs): void {
                    $q->whereIn('industries.id', $selectedSpecs);
                });
            }

            $selectedForms = array_values(array_filter((array) $request->query('form', []), fn ($v) => is_string($v) && $v !== ''));
            if ($selectedForms) {
                $query->whereHas('job', function ($q) use ($selectedForms): void {
                    $q->where(function ($qq) use ($selectedForms): void {
                        foreach ($selectedForms as $f) {
                            $qq->orWhereRaw('FIND_IN_SET(?, employment_form)', [$f]);
                        }
                    });
                });
            }

            $selectedRegion = $request->query('region');
            if (is_string($selectedRegion) && $selectedRegion !== '') {
                $region = Region::query()->where('slug', $selectedRegion)->first();
                if ($region) {
                    $name = $region->name;
                    $query->where(function ($q) use ($name): void {
                        $q->where('location', 'like', "%$name%")
                            ->orWhere('person_region', 'like', "%$name%")
                            ->orWhere('person_city', 'like', "%$name%")
                            ->orWhere('company_region', 'like', "%$name%")
                            ->orWhere('company_city', 'like', "%$name%");
                    });
                }
            }
        } elseif ($category->slug === 'szkolenia') {
            $trainingSpecs = AdTrainingSpecialization::query()->orderBy('name')->get(['id', 'name']);
            $regions = Region::query()->orderBy('name')->get(['id', 'name', 'slug']);

            $selectedTrainingSpecs = array_values(array_unique(array_map('intval', (array) $request->query('tspec', []))));
            if ($selectedTrainingSpecs) {
                $query->whereHas('training.specializations', function ($q) use ($selectedTrainingSpecs): void {
                    $q->whereIn('ad_training_specializations.id', $selectedTrainingSpecs);
                });
            }

            $selectedCert = (bool) $request->boolean('cert');
            if ($selectedCert) {
                $query->whereHas('training', fn ($q) => $q->where('has_certificate', true));
            }

            $selectedPriceFrom = $request->query('price_from');
            $selectedPriceTo = $request->query('price_to');
            if ($selectedPriceFrom !== null || $selectedPriceTo !== null) {
                $query->whereHas('training', function ($q) use ($selectedPriceFrom, $selectedPriceTo): void {
                    if ($selectedPriceFrom !== null && $selectedPriceFrom !== '') {
                        $q->whereRaw('COALESCE(promo_price, price) >= ?', [(float) $selectedPriceFrom]);
                    }
                    if ($selectedPriceTo !== null && $selectedPriceTo !== '') {
                        $q->whereRaw('COALESCE(promo_price, price) <= ?', [(float) $selectedPriceTo]);
                    }
                });
            }

            $selectedDateStart = $request->query('date_start');
            $selectedDateEnd = $request->query('date_end');
            if (($selectedDateStart && $selectedDateStart !== '') || ($selectedDateEnd && $selectedDateEnd !== '')) {
                $query->whereHas('training.dates', function ($q) use ($selectedDateStart, $selectedDateEnd): void {
                    if ($selectedDateStart && $selectedDateEnd) {
                        $q->whereBetween('start_date', [$selectedDateStart, $selectedDateEnd]);
                    } elseif ($selectedDateStart) {
                        $q->where('start_date', '>=', $selectedDateStart);
                    } elseif ($selectedDateEnd) {
                        $q->where('start_date', '<=', $selectedDateEnd);
                    }
                });
            }

            $selectedRegion = $request->query('region');
            if (is_string($selectedRegion) && $selectedRegion !== '') {
                $region = Region::query()->where('slug', $selectedRegion)->first();
                if ($region) {
                    $name = $region->name;
                    $query->where(function ($q) use ($name): void {
                        $q->where('location', 'like', "%$name%")
                            ->orWhere('person_region', 'like', "%$name%")
                            ->orWhere('person_city', 'like', "%$name%")
                            ->orWhere('company_region', 'like', "%$name%")
                            ->orWhere('company_city', 'like', "%$name%");
                    });
                }
            }
        } elseif ($category->slug === 'urzadzenia-i-sprzet') {
            $regions = Region::query()->orderBy('name')->get(['id', 'name', 'slug']);

            $selectedPriceFrom = $request->query('price_from');
            $selectedPriceTo = $request->query('price_to');
            if ($selectedPriceFrom !== null && $selectedPriceFrom !== '') {
                $query->where('price_from', '>=', (float) $selectedPriceFrom);
            }
            if ($selectedPriceTo !== null && $selectedPriceTo !== '') {
                $query->where('price_from', '<=', (float) $selectedPriceTo);
            }

            $states = array_values(array_filter((array) $request->query('state', []), fn ($s) => is_string($s) && $s !== ''));
            if ($states) {
                $query->whereHas('machines', fn ($q) => $q->whereIn('state', $states));
            }

            $financing = (bool) $request->boolean('financing');
            if ($financing) {
                $query->whereHas('machines', fn ($q) => $q->where('deposit_required', true));
            }

            $selectedRegion = $request->query('region');
            if (is_string($selectedRegion) && $selectedRegion !== '') {
                $region = Region::query()->where('slug', $selectedRegion)->first();
                if ($region) {
                    $name = $region->name;
                    $query->where(function ($q) use ($name): void {
                        $q->where('location', 'like', "%$name%")
                            ->orWhere('person_region', 'like', "%$name%")
                            ->orWhere('person_city', 'like', "%$name%")
                            ->orWhere('company_region', 'like', "%$name%")
                            ->orWhere('company_city', 'like', "%$name%");
                    });
                }
            }
        }

        $ads = $query->latest('id')->paginate(12);

        return view('sites/ads/index', [
            'ads' => $ads,
            'categories' => $categories,
            'selectedCategory' => $category,
            'subcategories' => $subcategories,
            'selectedSubcategory' => $subcategory,
            'industries' => $industries,
            'regions' => $regions,
            'selectedSpecs' => $selectedSpecs,
            'selectedForms' => $selectedForms,
            'selectedRegion' => $selectedRegion,
            'trainingSpecs' => $trainingSpecs,
            'selectedTrainingSpecs' => $selectedTrainingSpecs,
            'selectedCert' => $selectedCert,
            'selectedPriceFrom' => $selectedPriceFrom,
            'selectedPriceTo' => $selectedPriceTo,
            'selectedDateStart' => $selectedDateStart,
            'selectedDateEnd' => $selectedDateEnd,
            'selectedSort' => $selectedSort,
            'search' => $search,
            'city' => $city,
        ]);
    }


    protected function applySearch(Builder $query, ?string $term): void
    {
        $value = trim((string) ($term ?? ''));
        if ($value === '') {
            return;
        }

        $like = "%{$value}%";

        $query->where(function (Builder $q) use ($like): void {
            $q->where('title', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhereHas('job', function (Builder $j) use ($like): void {
                    $j->where('employment_form', 'like', $like)
                        ->orWhere('experience_level', 'like', $like)
                        ->orWhere('requirements', 'like', $like)
                        ->orWhere('benefits', 'like', $like)
                        ->orWhere('job_type', 'like', $like);
                })
                ->orWhereHas('machines', function (Builder $m) use ($like): void {
                    $m->where('state', 'like', $like)
                        ->orWhere('availability_type', 'like', $like)
                        ->orWhere('price_unit', 'like', $like);
                })
                ->orWhereHas('training', function (Builder $t) use ($like): void {
                    $t->where('organizer', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('program', 'like', $like)
                        ->orWhere('audience', 'like', $like);
                });
        });
    }

    protected function applyCity(Builder $query, ?string $city): void
    {
        $value = trim((string) ($city ?? ''));
        if ($value === '') {
            return;
        }

        $canonical = City::query()
            ->whereRaw('LOWER(name) = ?', [Str::lower($value)])
            ->value('name');

        $this->applyCityFilters($query, $canonical ?? $value);
    }

    private function applyCityFilters(Builder $query, string $value): void
    {
        $like = "%{$value}%";

        $query->where(function (Builder $q) use ($like): void {
            $q->where('location', 'like', $like)
                ->orWhere('person_region', 'like', $like)
                ->orWhere('person_city', 'like', $like)
                ->orWhere('company_region', 'like', $like)
                ->orWhere('company_city', 'like', $like);
        });
    }

}
