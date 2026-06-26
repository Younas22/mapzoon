<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class ProjectCredential extends Model
{
    public const PLATFORMS = [
        'google_account' => 'Google Account',
        'google_business_profile' => 'Google Business Profile',
        'hosting' => 'Hosting',
        'domain' => 'Domain',
        'cpanel' => 'cPanel',
        'wordpress' => 'WordPress',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'tiktok' => 'TikTok',
        'linkedin' => 'LinkedIn',
        'custom' => 'Custom Account',
    ];

    protected $fillable = [
        'project_id',
        'platform',
        'label',
        'username',
        'password',
        'recovery_email',
        'recovery_phone',
        'url',
        'notes',
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

    public function history(): HasMany
    {
        return $this->hasMany(ProjectCredentialHistory::class, 'credential_id')->latest();
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(CredentialAccessLog::class, 'credential_id')->latest();
    }

    public function platformLabel(): string
    {
        return self::PLATFORMS[$this->platform] ?? $this->platform;
    }

    /**
     * Snapshot the credential's current values into the history table. Called
     * after every create/update, and just before delete, so the table reads
     * as a full version timeline rather than a before/after diff.
     */
    public function snapshotHistory(string $action): ProjectCredentialHistory
    {
        return ProjectCredentialHistory::query()->create([
            'project_id' => $this->project_id,
            'credential_id' => $this->id,
            'action' => $action,
            'platform' => $this->platform,
            'label' => $this->label,
            'username' => $this->username,
            'password' => $this->password,
            'recovery_email' => $this->recovery_email,
            'recovery_phone' => $this->recovery_phone,
            'url' => $this->url,
            'notes' => $this->notes,
            'changed_by' => Auth::id(),
        ]);
    }
}
