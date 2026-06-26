<?php

namespace App\Models;

use App\Models\Concerns\FormatsFileSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskAttachment extends Model
{
    use FormatsFileSize;

    protected $fillable = [
        'task_id',
        'user_id',
        'path',
        'original_name',
        'size',
        'mime_type',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
