<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    protected $fillable = [
        'user_id',
        'ages',
        'currency_id',
        'start_date',
        'end_date',
        'trip_length',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'ages' => 'array',
            'start_date' => 'date',
            'end_date' => 'date',
            'trip_length' => 'integer',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
