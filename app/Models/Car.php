<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Car extends Model
{
    protected $fillable = [
        'model',
        'color',
        'license_plate_number',
        'date_of_manufacture'
    ];

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'date_of_manufacture' => 'integer'
        ];
    }
}
