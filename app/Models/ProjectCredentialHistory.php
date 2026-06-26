<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectCredentialHistory extends Model
{
    protected $table = 'project_credential_histories';

    protected $fillable = [
        'project_id',
        'credential_id',
        'action',
        'platform',
        'label',
        'username',
        'password',
        'recovery_email',
        'recovery_phone',
        'url',
        'notes',
        'changed_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'encrypted',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function credential(): BelongsTo
    {
        return $this->belongsTo(ProjectCredential::class, 'credential_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function platformLabel(): string
    {
        return ProjectCredential::PLATFORMS[$this->platform] ?? $this->platform;
    }
}
