<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum DocumentAvailability: int
{
    use HasEnumQuery, HasOptions;

    case NO_DOCUMENT = 0;
    case EXISTS = 1;
    case BY_TICKET = 2;
    case BAST = 3;

    public function getColor()
    {
        return match ($this) {
            self::EXISTS => 'primary',
            self::NO_DOCUMENT => 'danger',
            self::BY_TICKET => 'warning',
            self::BAST => 'success',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::EXISTS => 'Document Exists',
            self::NO_DOCUMENT => 'No Document',
            self::BY_TICKET => 'Confirmation By Ticket',
            self::BAST => 'Document E-BAST',
        };
    }
}
