<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
    ];


    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
