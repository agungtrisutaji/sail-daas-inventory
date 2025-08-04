<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum TicketStatus: int
{
    use HasEnumQuery, HasOptions;

    case NEW = 0;
    case INPROGRESS = 1;
    case RESOLVED = 2;
    case PENDING = 3;
    case CLOSED = 4;
    case CANCEL = 5;
    case UNKNOWN = 99;

    public function getColor()
    {
        return match ($this) {
            self::NEW => 'warning',
            self::INPROGRESS => 'info',
            self::RESOLVED => 'success',
            self::PENDING => 'danger',
            self::CLOSED => 'light',
            self::CANCEL => 'danger',
            self::UNKNOWN => 'dark',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::NEW => 'New Ticket',
            self::INPROGRESS => 'In Progress',
            self::RESOLVED => 'Resolved',
            self::PENDING => 'Pending',
            self::CLOSED => 'Closed',
            self::CANCEL => 'Cancelled',
            self::UNKNOWN => 'Unknown',
        };
    }
}
