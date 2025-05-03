<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditCard extends Model
{
    protected $fillable = [
        'exp_year',
        'exp_month',
        'cvv2',
        'card_number'
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

}
