<?php

namespace App\Enums;

enum ServiceCategory: int
{
    case LAPTOP_GNRL = 1;
    case LAPTOP_EXCT = 2;
    case LAPTOP_PRFM = 3;
    case DESKTOP_GNRL = 4;
    case DESKTOP_PRFM = 5;

    public function getColor()
    {
        return match ($this) {
            self::LAPTOP_GNRL => 'primary',
            self::LAPTOP_EXCT => 'success',
            self::LAPTOP_PRFM => 'warning',
            self::DESKTOP_GNRL => 'primary',
            self::DESKTOP_PRFM => 'warning',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::LAPTOP_GNRL => 'Laptop General',
            self::LAPTOP_EXCT => 'Laptop Exclusive',
            self::LAPTOP_PRFM => 'Laptop Preferred',
            self::DESKTOP_GNRL => 'Desktop General',
            self::DESKTOP_PRFM => 'Desktop Preferred',
        };
    }
}
