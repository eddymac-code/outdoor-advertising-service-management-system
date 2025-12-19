<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    #[Scope]
    protected function expired(Builder $query): void
    {
        $query->whereHas('asset', fn($q) => $q->where('status', 'available'));
    }
}
