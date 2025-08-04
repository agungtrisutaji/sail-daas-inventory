<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum UnitCategory: string
{
    use HasEnumQuery, HasOptions;
    case LAPTOP = 'Laptop';
    case DESKTOP = 'Desktop';
    case MONITOR = 'Monitor';
    case PART = 'Part';
    case OTHER = 'Other';

    public function getColor(): string
    {
        return match ($this) {
            self::LAPTOP => 'success',
            self::DESKTOP => 'primary',
            self::MONITOR => 'info',
            self::PART => 'warning',
            self::OTHER => 'secondary',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::LAPTOP => 'Laptop',
            self::DESKTOP => 'Desktop',
            self::MONITOR => 'Monitor',
            self::PART => 'Part',
            self::OTHER => 'Other',
        };
    }

    public function getValue(): string
    {
        return match ($this) {
            self::LAPTOP => 'laptop',
            self::DESKTOP => 'desktop',
            self::MONITOR => 'monitor',
            self::PART => 'part',
            self::OTHER => 'other',
        };
    }
}
