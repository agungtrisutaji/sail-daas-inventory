<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum TerminationStatus: int
{
    use HasEnumQuery, HasOptions;

    case NEW = 0;
    case INPROGRESS = 1;
    case NOTRETURN = 2;
    case BILLCLOSED = 3;
    case CLOSED = 4;
    case CANCEL = 5;
    case UNKNOWN = 99;

    public function getColor()
    {
        return match ($this) {
            self::NEW => 'warning',
            self::INPROGRESS => 'info',
            self::NOTRETURN => 'danger',
            self::BILLCLOSED => 'success',
            self::CLOSED => 'light',
            self::CANCEL => 'danger',
            self::UNKNOWN => 'dark',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::NEW => 'New Termination',
            self::INPROGRESS => 'In Progress',
            self::NOTRETURN => 'Not Return',
            self::BILLCLOSED => 'Bill Closed',
            self::CLOSED => 'Closed',
            self::CANCEL => 'Cancelled',
            self::UNKNOWN => 'Unknown',
        };
    }
}
