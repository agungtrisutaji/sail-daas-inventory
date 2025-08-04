<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum TerminationType: int
{
    use HasOptions, HasEnumQuery;

    case RENEWAL = 0;
    case TERMINATE_ONLY = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::RENEWAL => 'Renewal',
            self::TERMINATE_ONLY => 'Terminate Only',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::RENEWAL => 'primary',
            self::TERMINATE_ONLY => 'success',
        };
    }
}
