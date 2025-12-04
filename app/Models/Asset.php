<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'location',
        'latitude',
        'longitude',
        'size',
        'price_per_month',
        'status',
        'on_hold_expires_at'
    ];

    protected $dates = [
        'on_hold_expires_at'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
