<?php

namespace App\Livewire\Ads;

use App\Models\Ad;
use App\Models\AdJob;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateAd extends Component
{
    use WithFileUploads;

    public $categories = [];

    public $category_id = 0;

    public $selectedSlug = null;

    public string $title = '';

    public ?string $location = null;

    public $subcat_work = [];

    public $industries = [];

    public ?int $subcategory_id = null;

    public ?int $industry_id = null; 

    public ?string $employment_form = null;

    public ?float $salary_from = null;

    public ?float $salary_to = null;

    public ?string $experience_level = null;

    public ?string $scope = null;

    public ?string $requirements = null;

    public ?string $benefits = null;

    public $attachments = null;

    public ?string $site_www = null;

    public function mount(): void
    {
        $this->categories = Category::orderBy('name')->get();
    }

    public function updatedCategoryId($value): void
    {
        // dd('ZMIANA KATEGORII: '.$value);
        $category = collect($this->categories)->firstWhere('id', $value);
        $this->selectedSlug = $category?->slug;

        if ($this->selectedSlug === 'praca') {
            $this->subcat_work = Subcategory::query()
                ->where('category_id', (int) $value)
                ->orderBy('name')
                ->get();

            $this->industries = Industry::orderBy('name')->get();
        } else {
            $this->subcat_work = [];
            $this->industries = [];
        }
    }

    protected function rules(): array
    {
        $base = [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
        ];

        if ($this->selectedSlug === 'praca') {
            $job = [
                'subcategory_id' => ['required', 'integer', 'exists:subcategories,id'],
                'industry_id' => ['nullable', 'integer', 'exists:industries,id'],
                'employment_form' => ['required', 'string', 'max:255'],
                'location' => ['nullable', 'string', 'max:255'],
                'salary_from' => ['nullable', 'numeric', 'min:0'],
                'salary_to' => ['nullable', 'numeric', 'gte:salary_from'],
                'experience_level' => ['nullable', 'string'],
                'scope' => ['nullable', 'string'],
                'requirements' => ['nullable', 'string'],
                'benefits' => ['nullable', 'string'],
                'site_www' => ['nullable', 'url'],
            ];

            return array_merge($base, $job);
        }

        return $base;
    }

    public function save(): mixed
    {
        $validated = $this->validate();

        $ad = new Ad;
        $ad->user_id = Auth::id();
        $ad->category_id = $this->category_id;
        $ad->title = $this->title;
        $ad->description = $this->selectedSlug === 'praca' ? ($this->experience_level ?? null) : null;
        $ad->location = $this->location;
        $ad->status = 'active';
        $ad->save();
        Log::info('CreateAd: ad saved', [
            'ad_id' => $ad->id,
            'user_id' => $ad->user_id,
            'category_id' => $ad->category_id,
            'title' => $ad->title,
        ]);

        if ($this->selectedSlug === 'praca') {
            $job = new AdJob;
            $job->ad_id = $ad->id;
            $job->job_type = optional(Subcategory::find($this->subcategory_id))->slug ?? 'dam';
            $job->employment_form = (string) $this->employment_form;
            $job->salary_from = $this->salary_from;
            $job->salary_to = $this->salary_to;
            $job->experience_level = $this->experience_level;
            $job->requirements = $this->requirements;
            $job->benefits = $this->benefits;
            $job->save();
            Log::info('CreateAd: ad_job saved', [
                'ad_id' => $ad->id,
                'ad_job_id' => $job->id,
                'employment_form' => $job->employment_form,
                'salary_from' => $job->salary_from,
                'salary_to' => $job->salary_to,
            ]);

            if ($this->industry_id) {
                $job->specializations()->sync([$this->industry_id]);
            }
        }

        // Reset minimal state and close modal
        $this->dispatch('ad-created');
        $this->dispatch('modal-close', name: 'add-ad');
        $this->reset(['title', 'location', 'subcategory_id', 'industry_id', 'employment_form', 'salary_from', 'salary_to', 'experience_level', 'scope', 'requirements', 'benefits', 'attachments', 'site_www']);

        return redirect()->route('user-ads.index');
    }

    public function render()
    {
        return view('livewire.ads.create-ad');
    }
}
