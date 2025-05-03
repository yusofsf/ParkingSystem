<?php

namespace App\Enums;

enum PaymentType: string
{
    case CASH = 'Cash';
    case CREDIT_CARD = 'Credit Card';

    public function getPaymentType(): string
    {
        return match ($this) {
            self::CASH => self::CASH->value . ' Payment',
            self::CREDIT_CARD => self::CREDIT_CARD->value . ' Payment'
        };
    }
}
