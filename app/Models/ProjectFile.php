<?php

namespace App\Models;

use App\Models\Concerns\FormatsFileSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFile extends Model
{
    use FormatsFileSize;

    protected $fillable = [
        'project_id',
        'user_id',
        'path',
        'original_name',
        'size',
        'mime_type',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
