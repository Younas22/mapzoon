<?php

namespace App\Http\Requests\Admin\VideoReview;

use App\Models\VideoReview;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreVideoReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', VideoReview::class);
    }

    public function rules(): array
    {
        return [
            'client_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'review_text' => ['nullable', 'string', 'max:2000'],
            'youtube_url' => ['required', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(array_keys(VideoReview::STATUSES))],
            'is_visible_on_homepage' => ['boolean'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->filled('youtube_url') && ! VideoReview::extractYoutubeId($this->input('youtube_url'))) {
                $validator->errors()->add('youtube_url', 'Enter a valid YouTube video URL.');
            }
        });
    }
}
