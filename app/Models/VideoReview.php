<?php

namespace App\Models;

use App\Models\Concerns\HasInitials;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class VideoReview extends Model
{
    use HasInitials;

    public const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    protected $fillable = [
        'client_name',
        'tagline',
        'company_name',
        'review_text',
        'youtube_url',
        'thumbnail',
        'display_order',
        'status',
        'is_visible_on_homepage',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_visible_on_homepage' => 'boolean',
        ];
    }

    protected function initialsSourceAttribute(): string
    {
        return 'client_name';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeVisibleOnHomepage(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('is_visible_on_homepage', true);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Extracts the 11-character video ID from any common YouTube URL shape
     * (watch?v=, youtu.be/, embed/, shorts/) — not stored, computed on demand
     * so editing the source URL can never leave a stale cached ID behind.
     */
    public static function extractYoutubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function youtubeVideoId(): ?string
    {
        return self::extractYoutubeId($this->youtube_url);
    }

    public function thumbnailUrl(): ?string
    {
        if ($this->thumbnail) {
            return Storage::disk('public')->url($this->thumbnail);
        }

        if ($videoId = $this->youtubeVideoId()) {
            return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
        }

        return null;
    }

    public function embedUrl(): ?string
    {
        $videoId = $this->youtubeVideoId();

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }
}
