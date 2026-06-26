<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientContract extends Model
{
    public const STATUSES = [
        'active' => 'Active',
        'expired' => 'Expired',
        'terminated' => 'Terminated',
    ];

    protected $fillable = [
        'client_id',
        'title',
        'file_path',
        'file_original_name',
        'value',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

}
