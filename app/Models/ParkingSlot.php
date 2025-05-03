<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ParkingSlot extends Model
{
    protected $fillable = [
        'name',
        'type',
        'price_per_day',
        'price_per_hour',
        'available'
    ];

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }
}
