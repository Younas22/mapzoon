<?php

namespace App\Http\Requests\Admin\CaseStudy;

use App\Models\CaseStudy;
use Illuminate\Foundation\Http\FormRequest;

class StoreCaseStudyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', CaseStudy::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:case_studies,slug'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'gmb_link' => ['nullable', 'string', 'max:255'],
            'website_link' => ['nullable', 'string', 'max:255'],
            'video_url' => ['nullable', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'owner_review' => ['nullable', 'string', 'max:2000'],
            'display_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],

            'new_screenshots' => ['nullable', 'array'],
            'new_screenshots.*' => ['image', 'max:4096'],
            'existing_screenshots' => ['nullable', 'array'],
            'existing_screenshots.*' => ['string'],
        ];
    }
}
