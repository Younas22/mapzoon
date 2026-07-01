<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'gmb_link',
        'website_link',
        'screenshots',
        'owner_name',
        'owner_review',
        'video_url',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'screenshots' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function imageUrl(): ?string
    {
        return $this->image ? asset($this->image) : null;
    }

    public function screenshotUrls(): array
    {
        return collect($this->screenshots ?? [])->map(fn ($s) => asset($s))->all();
    }

    public function youtubeEmbedUrl(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/', $this->video_url, $matches);

        return isset($matches[1]) ? 'https://www.youtube.com/embed/'.$matches[1] : null;
    }
}
