<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'proposal_sent' => 'Proposal Sent',
        'won' => 'Won',
        'lost' => 'Lost',
    ];

    public const SOURCES = [
        'website_contact' => 'Website Contact Form',
        'book_consultation' => 'Book Consultation Form',
        'manual' => 'Manual Entry',
    ];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'business_name',
        'service',
        'message',
        'status',
        'source',
        'follow_up_date',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class)->latest();
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function sourceLabel(): string
    {
        return self::SOURCES[$this->source] ?? $this->source;
    }

    public function isFollowUpOverdue(): bool
    {
        return $this->follow_up_date && $this->follow_up_date->isPast() && ! in_array($this->status, ['won', 'lost']);
    }
}
