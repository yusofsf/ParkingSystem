<?php

namespace App\Enums;

enum UserRole: string
{
    case USER = 'Costumer';
    case ADMIN = 'Administrator';
    case MANAGER = 'Manage Parking';

    public function getAccessLevel(): int
    {
        return match ($this) {
            self::USER => 1,
            self::MANAGER => 2,
            self::ADMIN => 3
        };
    }
}
