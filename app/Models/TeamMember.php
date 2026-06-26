<?php

namespace App\Models;

use App\Models\Concerns\HasInitials;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    use HasInitials;

    public const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    protected $fillable = [
        'name',
        'photo',
        'designation',
        'bio',
        'email',
        'linkedin_url',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeVisibleOnHomepage(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('is_visible_on_homepage', true);
    }

    public function photoUrl(): ?string
    {
        return $this->photo ? Storage::disk('public')->url($this->photo) : null;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
