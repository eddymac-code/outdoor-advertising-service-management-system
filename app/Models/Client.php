<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'password'
    ];

    protected $hidden = ['password'];


    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
