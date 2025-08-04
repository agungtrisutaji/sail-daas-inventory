<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum TicketType: int
{
    use HasOptions, HasEnumQuery;

    case REQUEST = 1;
    case INCIDENT = 2;

    case CHANGE = 3;

    public function getLabel()
    {
        return match ($this) {
            self::REQUEST => 'Request',
            self::INCIDENT => 'Incident',
            self::CHANGE => 'Change',
        };
    }

    public function getColor()
    {
        return match ($this) {
            self::REQUEST => 'info',
            self::INCIDENT => 'warning',
            self::CHANGE => 'success',
        };
    }
}
