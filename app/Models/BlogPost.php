<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class BlogPost extends Model
{
    public const STATUSES = [
        'draft' => 'Draft',
        'published' => 'Published',
        'scheduled' => 'Scheduled',
    ];

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag');
    }

    public function seo(): HasOne
    {
        return $this->hasOne(BlogPostSeo::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(BlogPostFaq::class)->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at?->isPast();
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function featuredImageUrl(): ?string
    {
        return $this->featured_image ? Storage::disk('public')->url($this->featured_image) : null;
    }

    /**
     * Estimated reading time in minutes, derived from the word count of all
     * text-bearing content blocks (~200 words per minute).
     */
    public function readingTime(): int
    {
        $words = 0;

        foreach ($this->content ?? [] as $block) {
            $words += match ($block['type'] ?? null) {
                'paragraph', 'heading' => str_word_count($block['text'] ?? ''),
                'list' => array_sum(array_map('str_word_count', $block['items'] ?? [])),
                'quote' => str_word_count($block['text'] ?? ''),
                default => 0,
            };
        }

        return max(1, (int) ceil($words / 200));
    }
}
