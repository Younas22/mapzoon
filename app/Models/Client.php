<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    public const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'on_hold' => 'On Hold',
    ];

    public const CLIENT_TYPES = [
        'individual' => 'Individual',
        'business' => 'Business',
    ];

    protected $fillable = [
        'company_name',
        'owner_name',
        'phone',
        'email',
        'website',
        'address',
        'industry',
        'notes',
        'status',
        'client_type',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(ClientContact::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->latest();
    }

    public function files(): HasMany
    {
        return $this->hasMany(ClientFile::class)->latest();
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(ClientContract::class)->latest();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(ClientInvoice::class)->latest('issue_date');
    }

    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_team_member');
    }

    public function displayName(): string
    {
        return $this->company_name ?: $this->owner_name;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function clientTypeLabel(): string
    {
        return self::CLIENT_TYPES[$this->client_type] ?? $this->client_type;
    }
}
