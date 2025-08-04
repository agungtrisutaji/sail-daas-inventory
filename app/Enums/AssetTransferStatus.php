<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum AssetTransferStatus: int
{
    use HasOptions, HasEnumQuery;

    case PROCESSING = 0;
    case COMPLETED = 1;
    case CANCELLED = 2;
    case FAILED = 3;
    case UNKNOWN = 99;

    public function getColor()
    {
        return match ($this) {
            self::PROCESSING => 'warning',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
            self::FAILED => 'danger',
            self::UNKNOWN => 'dark',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::FAILED => 'Failed',
            self::UNKNOWN => 'Unknown',
        };
    }
}
