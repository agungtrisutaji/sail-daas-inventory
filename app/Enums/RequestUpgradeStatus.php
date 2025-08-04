<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum RequestUpgradeStatus: int
{
    use HasOptions, HasEnumQuery;

    case OFFERING = 0;
    case SENDING_PART = 1;
    case ASSIGNED = 2;
    case PROGRESS = 3;
    case DONE = 4;
    case PAID = 5;
    case CANCEL = 6;
    case CANCELLED = 7;
    case REJECTED = 8;
    case EXPIRED = 9;

    public function getColor()
    {
        return match ($this) {
            self::OFFERING => 'info',
            self::SENDING_PART => 'light',
            self::ASSIGNED => 'warning',
            self::PROGRESS => 'primary',
            self::DONE => 'light',
            self::PAID => 'success',
            self::CANCEL => 'warning',
            self::CANCELLED => 'danger',
            self::REJECTED => 'danger',
            self::EXPIRED => 'danger',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::OFFERING => 'Offering',
            self::SENDING_PART => 'Sending Part',
            self::ASSIGNED => 'Engineer Assigned',
            self::PROGRESS => 'in Progress',
            self::DONE => 'BAST Done',
            self::PAID => 'Paid',
            self::CANCEL => 'Cancel',
            self::CANCELLED => 'Cancelled',
            self::REJECTED => 'Rejected',
            self::EXPIRED => 'Expired',
        };
    }
}
