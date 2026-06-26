<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    public const STATUSES = [
        'planning' => 'Planning',
        'in_progress' => 'In Progress',
        'on_hold' => 'On Hold',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    public const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];

    protected $fillable = [
        'name',
        'project_type',
        'description',
        'client_id',
        'budget',
        'start_date',
        'end_date',
        'status',
        'priority',
        'progress',
        'services_included',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'progress' => 'integer',
            'services_included' => 'array',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_team_member');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ProjectNote::class)->latest();
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(ProjectDiscussion::class)->latest();
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class)->latest();
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(ProjectCredential::class)->latest();
    }

    public function credentialAccessLogs(): HasMany
    {
        return $this->hasMany(CredentialAccessLog::class)->latest();
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class)->orderBy('sort_order');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ProjectActivity::class)->latest();
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function priorityLabel(): string
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public function isOverdue(): bool
    {
        return $this->end_date && $this->end_date->isPast() && ! in_array($this->status, ['completed', 'cancelled']);
    }

    public function logActivity(string $type, string $description, array $meta = []): ProjectActivity
    {
        return $this->activities()->create([
            'user_id' => Auth::id(),
            'type' => $type,
            'description' => $description,
            'meta' => $meta,
        ]);
    }
}
