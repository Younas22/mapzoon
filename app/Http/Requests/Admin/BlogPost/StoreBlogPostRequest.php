<?php

namespace App\Http\Requests\Admin\BlogPost;

use App\Models\BlogPost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', BlogPost::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:blog_posts,slug'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'status' => ['required', Rule::in(array_keys(BlogPost::STATUSES))],
            'published_at' => ['nullable', 'date', 'required_if:status,scheduled'],
            'is_featured' => ['boolean'],
            'tags' => ['array'],
            'tags.*' => ['integer', 'exists:tags,id'],

            'content' => ['nullable', 'array'],
            'content.*.type' => ['required', Rule::in(['paragraph', 'heading', 'list', 'quote', 'table', 'image'])],
            'content.*.text' => ['nullable', 'string'],
            'content.*.cite' => ['nullable', 'string', 'max:255'],
            'content.*.items' => ['nullable', 'array'],
            'content.*.items.*' => ['nullable', 'string', 'max:1000'],
            'content.*.headers' => ['nullable', 'array'],
            'content.*.headers.*' => ['nullable', 'string', 'max:255'],
            'content.*.rows' => ['nullable', 'array'],
            'content.*.rows.*.*' => ['nullable', 'string', 'max:1000'],
            'content.*.image_url' => ['nullable', 'string', 'max:2048'],
            'content.*.caption' => ['nullable', 'string', 'max:255'],

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
            'seo.enable_article_schema' => ['boolean'],
            'seo.enable_breadcrumb_schema' => ['boolean'],
            'seo.enable_faq_schema' => ['boolean'],
        ];
    }
}
