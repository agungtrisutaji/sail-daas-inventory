<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum RequestCategory: int
{
    use HasOptions, HasEnumQuery;

    case NEW = 0;
    case RENEWAL = 1;
    case REPLACEMENT = 2;
    case UPGRADE = 3;

    public function getColor()
    {
        return match ($this) {
            self::NEW => 'primary',
            self::RENEWAL => 'success',
            self::REPLACEMENT => 'info',
            self::UPGRADE => 'warning',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::NEW => 'New Request',
            self::RENEWAL => 'Renewal',
            self::REPLACEMENT => 'Replacement',
            self::UPGRADE => 'Upgrade',
        };
    }
}
