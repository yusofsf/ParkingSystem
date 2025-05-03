<?php

namespace App\Enums;

enum ParkingSlotType: string
{
    case PREMIUM = 'Premium';
    case ECONOMIC = 'Economic';
    case BUSINESS = 'Business';

    public function getParkingSlotLevel(): int
    {
        return match ($this) {
            self::ECONOMIC => 1,
            self::BUSINESS => 2,
            self::PREMIUM => 3
        };
    }
}
