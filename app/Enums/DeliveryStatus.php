<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum DeliveryStatus: int
{
    use HasOptions, HasEnumQuery;

    case PROCESSING = 0;
    case COMPLETED = 1;
    case DELIVERY = 2;
    case PENDING = 3;
    case CANCELLED = 4;

    public function getColor()
    {
        return match ($this) {
            self::PROCESSING => 'primary',
            self::DELIVERY => 'success',
            self::COMPLETED => 'success',
            self::PENDING => 'warning',
            self::CANCELLED => 'light',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::PROCESSING => 'Processing',
            self::DELIVERY => 'In Delivery',
            self::COMPLETED => 'Completed',
            self::PENDING => 'Pending',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function getNextStatus()
    {
        return match ($this) {
            self::PROCESSING => self::DELIVERY,
            self::DELIVERY => self::COMPLETED,
        };
    }
}
