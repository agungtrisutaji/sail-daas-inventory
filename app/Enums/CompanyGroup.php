<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum CompanyGroup: string
{
    use HasEnumQuery, HasOptions;

    case TMT = 'TMT';
    case TU = 'TU';
    case MAHADASHA = 'MAHADASHA';
    case ABM = 'ABM';
    case MTT = 'MTT';

    public function getLabel()
    {
        return match ($this) {
            self::TMT => 'PT TIARA MARGA TRAKINDO',
            self::TU => 'PT TRAKINDO UTAMA',
            self::MAHADASHA => 'PT MAHADANA DASHA UTAMA',
            self::ABM => 'PT ABM INVESTAMA TBK',
            self::MTT => 'PT MACRO TREND TECHNOLOGY',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::TMT => 'danger',
            self::TU => 'success',
            self::MAHADASHA => 'warning',
            self::ABM => 'info',
            self::MTT => 'primary',
        };
    }

    public function getvalue(): string
    {
        return match ($this) {
            self::TMT => 'TMT',
            self::TU => 'TU',
            self::MAHADASHA => 'MAHADASHA',
            self::ABM => 'ABM',
            self::MTT => 'MTT',
        };
    }
}
