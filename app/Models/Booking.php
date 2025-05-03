<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'begin',
        'end',
        'is_paid',
        'cancelled',
        'car_id',
        'parking_slot_id'
    ];

    protected $attributes = [
        'is_paid' => false,
        'cancelled' => false
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function parkingSlot(): BelongsTo
    {
        return $this->belongsTo(ParkingSlot::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
