<?php

namespace App\Models;

use App\Models\Concerns\FormatsFileSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientFile extends Model
{
    use FormatsFileSize;

    protected $fillable = [
        'client_id',
        'user_id',
        'path',
        'original_name',
        'size',
        'mime_type',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
