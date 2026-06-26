<?php

namespace App\Http\Requests\Admin\BlogPost;

use App\Models\BlogPost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('blog_post'));
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_posts', 'slug')->ignore($this->route('blog_post'))],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'status' => ['required', Rule::in(array_keys(BlogPost::STATUSES))],
            'published_at' => ['nullable', 'date', 'required_if:status,scheduled'],
            'is_featured' => ['boolean'],
            'tags' => ['array'],
            'tags.*' => ['integer', 'exists:tags,id'],

            'content' => ['nullable', 'string'],

            'faqs' => ['nullable', 'array'],
            'faqs.*.question' => ['nullable', 'string', 'max:500'],
            'faqs.*.answer' => ['nullable', 'string', 'max:5000'],

            'seo' => ['nullable', 'array'],
            'seo.meta_title' => ['nullable', 'string', 'max:255'],
            'seo.meta_description' => ['nullable', 'string', 'max:500'],
            'seo.focus_keyword' => ['nullable', 'string', 'max:255'],
            'seo.canonical_url' => ['nullable', 'string', 'max:255'],
            'seo.og_title' => ['nullable', 'string', 'max:255'],
            'seo.og_description' => ['nullable', 'string', 'max:500'],
            'seo.twitter_card' => ['nullable', Rule::in(['summary', 'summary_large_image'])],
            'seo.twitter_title' => ['nullable', 'string', 'max:255'],
            'seo.twitter_description' => ['nullable', 'string', 'max:500'],
            'seo.custom_schema' => ['nullable', 'json'],
        ];
    }
}
