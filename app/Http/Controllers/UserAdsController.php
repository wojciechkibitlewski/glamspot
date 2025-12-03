<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Models\Ad;
use App\Models\AdJob;
use App\Models\AdMachine;
use App\Models\AdPhoto;
use App\Models\AdTraining;
use App\Models\AdTrainingDate;
use App\Models\AdTrainingSpecialization;
use App\Models\Category;
use App\Models\City;
use App\Models\Industry;
use App\Models\Region;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserAdsController extends Controller
{
    /////////////////////
    //
    // Funkcje pomocnicze 
    //
    /////////////////////
    protected function ensureCityExists(?string $cityName, ?string $regionName): void
    {
        $name = trim((string) ($cityName ?? ''));
        $regionText = trim((string) ($regionName ?? ''));
        if ($name === '' || $regionText === '') {
            return;
        }

        $region = Region::query()->where('name', $regionText)->first();
        if (! $region) {
            return;
        }

        $slug = Str::slug($name);
        $exists = City::query()
            ->where('region_id', $region->id)
            ->where('slug', $slug)
            ->exists();
        if (! $exists) {
            City::create([
                'region_id' => $region->id,
                'name' => $name,
                'slug' => $slug,
            ]);
        }
    }

    /////////////////////
    //
    // CRUD
    //
    /////////////////////

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $ads = Ad::query()
            ->where('user_id', $user->id)
            ->with(['category', 'photos'])
            ->latest('id')
            ->paginate(10);

        return view('sites/user-ads/index', [
            'ads' => $ads,
        ]);
    }

    public function show(string $code, string $slug)
    {
        /** @var User $user */
        $user = Auth::user();

        $ad = Ad::query()
            ->with(['category', 'photos', 'job.specializations', 'job.subcategory', 'training', 'training.dates', 'machines', 'user.firm'])
            ->where('user_id', $user->id)
            ->where('code', $code)
            ->firstOrFail();

        $expected = Str::slug((string) $ad->title);
        if ($slug !== $expected) {
            return redirect()->route('user-ads.show', [$ad->code, $expected]);
        }

        return view('sites/user-ads/show', [
            'ad' => $ad,
        ]);
    }

    public function create()
    {
        Session::forget('ad_photos_pending');

        $categories = Category::query()->orderBy('name')->get();
        $subcategories = Subcategory::query()->orderBy('name')->get(['id', 'name', 'slug', 'category_id']);
        $industries = Industry::query()->orderBy('name')->get(['id', 'name']);
        $trainingSpecs = AdTrainingSpecialization::query()->orderBy('name')->get(['id', 'name']);
        $selectedTrainingSpecIds = [];

        return view('sites/user-ads/create', [
            'categories' => $categories,
            'subcategories' => $subcategories,
            'industries' => $industries,
            'trainingSpecs' => $trainingSpecs,
        ]);
    }

    public function store(StoreAdRequest $request)
    {

        $user = $request->user();
        $validated = $request->validated();

        $category = Category::query()->findOrFail((int) $validated['category_id']);

        $ad = Ad::create([
            'user_id' => $user->id,
            'category_id' => (int) $validated['category_id'],
            'title' => $validated['title'],
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'as_company' => $request->boolean('as_company'),
            'status' => 'pending_payment',
            'is_featured' => 0,
        ]);

        // dane kontaktowe
        if ($request->boolean('as_company') && $user->firm) {
            $firm = $user->firm;
            $ad->update([
                'company_name' => $firm->firm_name,
                'company_address' => $validated['company_address'] ?? $firm->firm_address,
                'company_postalcode' => $validated['company_postalcode'] ?? $firm->firm_postalcode,
                'company_region' => $validated['company_region'] ?? $firm->firm_region,
                'company_city' => $validated['company_city'] ?? $firm->firm_city,
                'company_phone' => $validated['company_phone'] ?? $firm->firm_phone,
                'company_email' => $validated['company_email'] ?? $firm->firm_email,
            ]);
            $this->ensureCityExists($validated['company_city'] ?? $firm->firm_city, $validated['company_region'] ?? $firm->firm_region);
        } else {
            $ad->update([
                'person_name' => $validated['person_name'] ?? $user->name,
                'person_region' => $validated['person_region'] ?? null,
                'person_city' => $validated['person_city'] ?? $user->city,
                'person_phone' => $validated['person_phone'] ?? $user->phone,
                'person_email' => $validated['person_email'] ?? $user->email,
            ]);
            $this->ensureCityExists($validated['person_city'] ?? $user->city, $validated['person_region'] ?? null);
        }

        if ($category->slug === 'praca') {
            $adJob = AdJob::create([
                'ad_id' => $ad->id,
                'job_type' => 'dam-prace',
                'employment_form' => implode(',', $validated['employment_form'] ?? []),
                'salary_from' => $validated['salary_from'] ?? null,
                'salary_to' => $validated['salary_to'] ?? null,
                'experience_level' => $validated['experience_level'] ?? null,
                'requirements' => $validated['requirements'] ?? null,
                'benefits' => $validated['benefits'] ?? null,
            ]);

            if (! empty($validated['job_specializations'])) {
                $adJob->specializations()->sync($validated['job_specializations']);
            }
        } elseif ($category->slug === 'szkolenia') {
            $seatsRaw = $validated['seats'] ?? null;
            $seatsValue = is_numeric($seatsRaw) ? (int) $seatsRaw : null;
            $audienceValue = $validated['audience'] ?? null;
            if (! $audienceValue && $seatsRaw && ! is_numeric($seatsRaw)) {
                $audienceValue = $seatsRaw;
            }

            $adTraining = AdTraining::create([
                'ad_id' => $ad->id,
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'is_online' => $request->boolean('is_online'),
                'type' => $request->boolean('is_online') ? 'online' : 'stacjonarne',
                'price' => $validated['price'] ?? null,
                'promo_price' => $validated['promo_price'] ?? null,
                'seats' => $seatsValue,
                'audience' => $audienceValue,
                'description' => $validated['description'] ?? null,
                'program' => $validated['program'] ?? null,
                'bonuses' => $validated['bonuses'] ?? null,
                'signup_url' => $validated['signup_url'] ?? null,
                'has_certificate' => $request->boolean('certificate'),
            ]);

            if (! empty($validated['training_dates'])) {
                foreach ($validated['training_dates'] as $date) {
                    if (! empty($date['date'])) {
                        AdTrainingDate::create([
                            'ad_training_id' => $adTraining->id,
                            'start_date' => $date['date'],
                            'end_date' => $date['date'],
                        ]);
                    }
                }
            }

            $specIds = array_map('intval', (array) ($validated['training_specializations'] ?? []));
            $adTraining->specializations()->sync($specIds);
        } elseif ($category->slug === 'urzadzenia-i-sprzet') {
            $sub = Subcategory::query()->find((int) ($validated['subcategory_id'] ?? 0));
            $subSlug = $sub?->slug;

            $availabilityType = null;
            $priceUnit = null;
            if ($subSlug === 'urzadzenia-na-wynajem') {
                $availabilityType = 'rent';
                $priceUnit = $validated['price_unit'] ?? null;
            } else {
                $availabilityType = $validated['availability_type'] ?? null; 
            }

            $ad->update([
                'description' => $validated['description'] ?? null,
                'price_from' => $validated['price_from'] ?? null,
            ]);

            AdMachine::create([
                'ad_id' => $ad->id,
                'state' => $validated['state'] ?? null,
                'availability_type' => $availabilityType,
                'price_unit' => $priceUnit,
                'deposit_required' => $request->boolean('deposit_required'),
                'subcategory_id' => $validated['subcategory_id'] ?? null,
            ]);
        } else {
            $ad->update([
                'description' => $validated['description'] ?? null,
            ]);
        }

        $queuedPhotos = Session::get('ad_photos_pending', []);
        if (! empty($queuedPhotos)) {
            foreach ($queuedPhotos as $path) {
                AdPhoto::create([
                    'ad_id' => $ad->id,
                    'photo' => $path,
                ]);
            }
            Session::forget('ad_photos_pending');
        }

        return redirect()->route('user-ads.index')
            ->with('status', __('user-ads.status.saved'));
    }
    
    public function edit(string $code, string $slug)
    {
        /** @var User $user */
        $user = Auth::user();

        $ad = Ad::query()
            ->with([
                'category',
                'job',
                'job.subcategory',
                'job.specializations',
                'training',
                'training.specializations',
                'training.dates',
                'machines',
                'photos',
                'user.firm',
            ])
            ->where('user_id', $user->id)
            ->where('code', $code)
            ->firstOrFail();

        $expected = Str::slug((string) $ad->title);
        if ($slug !== $expected) {
            return redirect()->route('user-ads.edit', [$ad->code, $expected]);
        }

        $categories = Category::query()->orderBy('name')->get();
        $subcategories = Subcategory::query()->orderBy('name')->get(['id', 'name', 'slug', 'category_id']);
        $industries = Industry::query()->orderBy('name')->get(['id', 'name']);
        $trainingSpecs = AdTrainingSpecialization::query()->orderBy('name')->get(['id', 'name']);

        $selectedSubcategoryId = null;
        if ($ad->job && $ad->job->subcategory) {
            $selectedSubcategoryId = $ad->job->subcategory->id;
        } elseif ($ad->training) {
            $selectedSubcategoryId = $ad->training->subcategory_id;
        } elseif ($ad->machines) {
            $selectedSubcategoryId = $ad->machines->subcategory_id;
        }

        $trainingDates = [];
        if ($ad->training && $ad->training->dates) {
            $trainingDates = $ad->training->dates->map(function ($d) {
                return [
                    'date' => optional($d->start_date)?->format('Y-m-d'),
                ];
            })->all();
        }
        $selectedTrainingSpecIds = $ad->training
        ? $ad->training->specializations->pluck('id')->map(fn ($v) => (int) $v)->all()
        : [];

        // dd($ad);
        return view('sites/user-ads/edit', [
            'ad' => $ad,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'industries' => $industries,
            'trainingSpecs' => $trainingSpecs,
            'selectedSubcategoryId' => $selectedSubcategoryId,
            'trainingDates' => $trainingDates,
            'selectedTrainingSpecIds' => $selectedTrainingSpecIds,

        ]);
    }

    public function update(StoreAdRequest $request, string $code, string $slug): RedirectResponse
    {
        // dd($request);
        $user = $request->user();
        $ad = Ad::query()
            ->with(['category', 'job.specializations', 'training.specializations', 'training.dates'])
            ->where('user_id', $user->id)
            ->where('code', $code)
            ->firstOrFail();

        $validated = $request->validated();

        $category = Category::query()->findOrFail((int) $validated['category_id']);

        $ad->update([
            'category_id' => (int) $validated['category_id'],
            'title' => $validated['title'],
            'location' => $validated['location'] ?? null,
            'as_company' => $request->boolean('as_company'),
        ]);

        if ($request->boolean('as_company') && $user->firm) {
            $firm = $user->firm;
            $ad->update([
                'company_name' => $firm->firm_name,
                'company_address' => $validated['company_address'] ?? $firm->firm_address,
                'company_postalcode' => $validated['company_postalcode'] ?? $firm->firm_postalcode,
                'company_region' => $validated['company_region'] ?? $firm->firm_region,
                'company_city' => $validated['company_city'] ?? $firm->firm_city,
                'company_phone' => $validated['company_phone'] ?? $firm->firm_phone,
                'company_email' => $validated['company_email'] ?? $firm->firm_email,
                'person_name' => null,
                'person_region' => null,
                'person_city' => null,
                'person_phone' => null,
                'person_email' => null,
            ]);
            $this->ensureCityExists($validated['company_city'] ?? $firm->firm_city, $validated['company_region'] ?? $firm->firm_region);
            if ($request->boolean('update_firm')) {
                $firm->update([
                    'firm_name' => $validated['company_name'] ?? $firm->firm_name,
                    'firm_address' => $validated['company_address'] ?? $firm->firm_address,
                    'firm_postalcode' => $validated['company_postalcode'] ?? $firm->firm_postalcode,
                    'firm_region' => $validated['company_region'] ?? $firm->firm_region,
                    'firm_city' => $validated['company_city'] ?? $firm->firm_city,
                    'firm_email' => $validated['company_email'] ?? $firm->firm_email,
                    'firm_phone' => $validated['company_phone'] ?? $firm->firm_phone,
                ]);
                if (! empty($validated['company_region'])) {
                    $region = Region::query()->where('name', $validated['company_region'])->first();
                    if ($region) {
                        $firm->update(['firm_region_id' => $region->id]);
                    }
                }
            }
        } else {
            $ad->update([
                'person_name' => $validated['person_name'] ?? $user->name,
                'person_region' => $validated['person_region'] ?? null,
                'person_city' => $validated['person_city'] ?? $user->city,
                'person_phone' => $validated['person_phone'] ?? $user->phone,
                'person_email' => $validated['person_email'] ?? $user->email,
                'company_name' => null,
                'company_address' => null,
                'company_postalcode' => null,
                'company_region' => null,
                'company_city' => null,
                'company_phone' => null,
                'company_email' => null,
            ]);
            $this->ensureCityExists($validated['person_city'] ?? $user->city, $validated['person_region'] ?? null);
            if ($request->boolean('update_user')) {
                $user->update([
                    'name' => $validated['person_name'] ?? $user->name,
                    'city' => $validated['person_city'] ?? $user->city,
                    'phone' => $validated['person_phone'] ?? $user->phone,
                    'email' => $validated['person_email'] ?? $user->email,
                ]);
            }
        }

        if ($category->slug === 'praca') {

            $adJob = $ad->job;
            if (! $adJob) {
                $adJob = AdJob::create([
                    'ad_id' => $ad->id,
                    'job_type' => 'dam-prace',
                ]);
            }

            $adJob->update([
                'job_type' => 'dam-prace',
                'employment_form' => implode(',', $validated['employment_form'] ?? []),
                'salary_from' => $validated['salary_from'] ?? null,
                'salary_to' => $validated['salary_to'] ?? null,
                'experience_level' => $validated['experience_level'] ?? null,
                'requirements' => $validated['requirements'] ?? null,
                'benefits' => $validated['benefits'] ?? null,
            ]);

            $adJob->specializations()->sync($validated['job_specializations'] ?? []);

        } elseif ($category->slug === 'szkolenia') {
            $adTraining = $ad->training;
            if (! $adTraining) {
                $adTraining = AdTraining::create([
                    'ad_id' => $ad->id,
                ]);
            }

            $seatsRaw = $validated['seats'] ?? null;
            $seatsValue = is_numeric($seatsRaw) ? (int) $seatsRaw : null;
            $audienceValue = $validated['audience'] ?? null;
            if (! $audienceValue && $seatsRaw && ! is_numeric($seatsRaw)) {
                $audienceValue = $seatsRaw;
            }

            $adTraining->update([
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'is_online' => $request->boolean('is_online'),
                'type' => $request->boolean('is_online') ? 'online' : 'stacjonarne',
                'price' => $validated['price'] ?? null,
                'promo_price' => $validated['promo_price'] ?? null,
                'seats' => $seatsValue,
                'audience' => $audienceValue,
                'description' => $validated['description'] ?? null,
                'program' => $validated['program'] ?? null,
                'bonuses' => $validated['bonuses'] ?? null,
                'signup_url' => $validated['signup_url'] ?? null,
                'has_certificate' => $request->boolean('certificate'),
            ]);

            if (isset($validated['training_dates'])) {
                foreach ($adTraining->dates as $date) {
                    $date->delete();
                }
                foreach ($validated['training_dates'] as $date) {
                    if (! empty($date['date'])) {
                        AdTrainingDate::create([
                            'ad_training_id' => $adTraining->id,
                            'start_date' => $date['date'],
                            'end_date' => $date['date'],
                        ]);
                    }
                }
            }

            $specIds = array_values(array_unique(array_map('intval', (array) $request->input('training_specializations', []))));
            $adTraining->specializations()->sync($specIds);

        } elseif ($category->slug === 'urzadzenia-i-sprzet') {
            $sub = Subcategory::query()->find((int) ($validated['subcategory_id'] ?? 0));
            $subSlug = $sub?->slug;

            $availabilityType = null;
            $priceUnit = null;
            if ($subSlug === 'urzadzenia-na-wynajem') {
                $availabilityType = 'rent';
                $priceUnit = $validated['price_unit'] ?? null;
            } else {
                $availabilityType = $validated['availability_type'] ?? null; 
            }

            $machine = $ad->machines()->first();
            if (! $machine) {
                $machine = AdMachine::create(['ad_id' => $ad->id]);
            }
            $machine->update([
                'state' => $validated['state'] ?? null,
                'availability_type' => $availabilityType,
                'price_unit' => $priceUnit,
                'deposit_required' => $request->boolean('deposit_required'),
                'subcategory_id' => $validated['subcategory_id'] ?? null,
            ]);

            $ad->update([
                'description' => $validated['description'] ?? null,
                'price_from' => $validated['price_from'] ?? null,
            ]);
        } else {
            $ad->update([
                'description' => $validated['description'] ?? null,
            ]);
        }

        $queuedPhotos = Session::get('ad_photos_pending', []);
        if (! empty($queuedPhotos)) {
            foreach ($queuedPhotos as $path) {
                AdPhoto::create([
                    'ad_id' => $ad->id,
                    'photo' => $path,
                ]);
            }
            Session::forget('ad_photos_pending');
        }

        return redirect()->route('user-ads.edit', [$ad->code, $ad->slug])
            ->with('status', __('user-ads.status.updated'));
    }

    public function destroy(Request $request, string $code, string $slug): RedirectResponse
    {
        $user = $request->user();

        $ad = Ad::query()
            ->with('photos')
            ->where('user_id', $user->id)
            ->where('code', $code)
            ->firstOrFail();

        foreach ($ad->photos as $photo) {
            if ($photo->photo && Storage::disk('public')->exists($photo->photo)) {
                Storage::disk('public')->delete($photo->photo);
            }
        }

        $ad->delete();

        return redirect()->route('user-ads.index')
            ->with('status', __('user-ads.status.deleted'));
    }

    
    
    public function checkout(string $code, string $slug)
    {
        /** @var User $user */
        $user = Auth::user();

        $ad = Ad::query()
            ->where('user_id', $user->id)
            ->where('code', $code)
            ->firstOrFail();

        $expected = Str::slug((string) $ad->title);
        if ($slug !== $expected) {
            return redirect()->route('user-ads.checkout', [$ad->code, $expected]);
        }

        
        /// to do poprawy
        $fee = (string) config('payments.ad_post_fee');
        /// to do poprawy


        return view('sites/user-ads/checkout', [
            'ad' => $ad,
            'fee' => $fee,
        ]);
    }


}
