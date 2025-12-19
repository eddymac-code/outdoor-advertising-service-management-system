<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected $casts = [
        'on_hold_expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Fires BEFORE status update - set expiry automatically
        static::updating(function ($asset) {
            if ($asset->isDirty('status') && $asset->status === 'on_hold') {
                $asset->on_hold_expires_at = DateHelper::addBusinessDays(
                    Carbon::now(), 
                    3
                );
            }
            
            // Auto-clear expired holds (optional, cron still runs)
            if ($asset->status === 'on_hold' && 
                $asset->on_hold_expires_at && 
                $asset->on_hold_expires_at->isPast()) {
                $asset->status = 'available';
                $asset->on_hold_expires_at = null;
            }
        });
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    #[Scope]
    protected function available(Builder $query): void
    {
        $query->where('status', 'available');
    }
}
