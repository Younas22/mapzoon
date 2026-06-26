<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CredentialAccessLog extends Model
{
    protected $fillable = [
        'project_id',
        'credential_id',
        'history_id',
        'user_id',
        'action',
        'ip_address',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function credential(): BelongsTo
    {
        return $this->belongsTo(ProjectCredential::class, 'credential_id');
    }

    public function history(): BelongsTo
    {
        return $this->belongsTo(ProjectCredentialHistory::class, 'history_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
