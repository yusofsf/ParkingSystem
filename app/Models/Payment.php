<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $fillable = [
        'total_price',
        'type',
        'details'
    ];

    public function cash(): HasOne
    {
        return $this->HasOne(Cash::class);
    }

    public function creditCard(): HasOne
    {
        return $this->HasOne(CreditCard::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
