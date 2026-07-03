<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'client_id',
        'owner_name',
        'owner_role',
        'owner_photo',
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

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function imageUrl(): ?string
    {
        return $this->image ? asset($this->image) : null;
    }

    /**
     * Falls back to the linked client's photo when this case study has no
     * photo of its own uploaded — lets an admin reuse a client's existing
     * display picture instead of re-uploading it per case study.
     */
    public function ownerPhotoUrl(): ?string
    {
        if ($this->owner_photo) {
            return asset($this->owner_photo);
        }

        return $this->client?->photoUrl();
    }

    public function ownerRoleLabel(): string
    {
        return $this->owner_role ?: 'Client';
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

        preg_match('/(?:youtube\.com\/(?:watch\?v=|shorts\/|embed\/|live\/)|youtu\.be\/)([\w-]+)/', $this->video_url, $matches);

        return isset($matches[1]) ? 'https://www.youtube.com/embed/'.$matches[1] : null;
    }
}
