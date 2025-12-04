<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'client_id',
        'asset_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];


    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
