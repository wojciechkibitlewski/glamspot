<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();
        if (isset($data['signup_url']) && is_string($data['signup_url'])) {
            $url = trim($data['signup_url']);
            if ($url !== '' && ! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
                $data['signup_url'] = 'https://'.$url;
            }
        }
        if (isset($data['training_specializations']) && is_array($data['training_specializations'])) {
            $normalized = [];
            foreach ($data['training_specializations'] as $val) {
                if (is_numeric($val)) {
                    $normalized[] = (int) $val;
                }
            }
            $data['training_specializations'] = array_values(array_unique($normalized));
        }
        $this->replace($data);
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer'],
            // subcategory will be required only for specific categories (e.g. szkolenia, urzadzenia-i-sprzet)
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
            // work
            'job_specializations' => ['array'],
            'job_specializations.*' => ['integer', 'exists:industries,id'],
            'employment_form' => ['required', 'array', 'min:1'],
            'employment_form.*' => ['string', 'max:64'],
            'salary_from' => ['nullable', 'numeric', 'min:0'],
            'salary_to' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'experience_level' => ['nullable', 'string'],
            'scope' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            // courses
            'training_specializations' => ['sometimes', 'array'],
            'training_specializations.*' => ['nullable', 'integer', 'exists:ad_training_specializations,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'seats' => ['nullable', 'string', 'max:255'],
            'audience' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'program' => ['nullable', 'string'],
            'bonuses' => ['nullable', 'string'],
            'signup_url' => ['nullable', 'url', 'max:255'],
            'certificate' => ['nullable'],
            'training_dates' => ['array'],
            'is_online' => ['nullable'],
            // devices (Urządzenia i sprzęt)
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'deposit_required' => ['nullable', 'boolean'],
            // contact
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'company_postalcode' => ['nullable', 'string', 'max:32'],
            'company_region' => ['nullable', 'string', 'max:255'],
            'company_city' => ['nullable', 'string', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:64'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'person_name' => ['nullable', 'string', 'max:255'],
            'person_region' => ['nullable', 'string', 'max:255'],
            'person_city' => ['nullable', 'string', 'max:255'],
            'person_phone' => ['nullable', 'string', 'max:64'],
            'person_email' => ['nullable', 'email', 'max:255'],

        ];

        // Determine selected category slug
        $categorySlug = null;
        if ($this->input('category_id')) {
            $categorySlug = optional(Category::query()->find((int) $this->input('category_id')))->slug;
        }

        // Job-specific rules should apply only for "praca"
        if ($categorySlug !== 'praca') {
            $rules['employment_form'] = ['nullable', 'array'];
            $rules['employment_form.*'] = ['string', 'max:64'];
        }

        // Enforce subcategory only for categories that use it
        if (in_array($categorySlug, ['szkolenia', 'urzadzenia-i-sprzet'], true)) {
            $rules['subcategory_id'] = ['required', 'integer', 'exists:subcategories,id'];
        }

        // Conditional validation for devices subcategories
        $subSlug = null;
        if ($this->input('subcategory_id')) {
            $subSlug = optional(Subcategory::query()->find((int) $this->input('subcategory_id')))->slug;
        }

        if (in_array($subSlug, ['urzadzenia-nowe', 'urzadzenia-uzywane', 'urzadzenia-na-wynajem'], true)) {
            $rules['state'] = ['required', 'in:bardzo_dobry,dobry,wymaga_naprawy'];
        }

        if (in_array($subSlug, ['urzadzenia-nowe', 'urzadzenia-uzywane'], true)) {
            $rules['availability_type'] = ['required', 'in:sale,buy'];
            $rules['price_unit'] = ['nullable', 'in:hour,day,week,month'];
        } elseif ($subSlug === 'urzadzenia-na-wynajem') {
            $rules['price_unit'] = ['required', 'in:hour,day,week,month'];
            $rules['availability_type'] = ['nullable', 'in:sale,buy'];
        }

        return $rules;
    }
}
