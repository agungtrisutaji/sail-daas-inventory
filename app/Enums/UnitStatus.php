<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum UnitStatus: int
{
    use HasOptions, HasEnumQuery;
    case AVAILABLE  = 0;
    case STAGING    = 1;
    case DELIVERY   = 2;
    case DEPLOYMENT = 3;
    case TERMINATION = 4;
    case TERMINATED = 5;
    case ACTIVE       = 6;
    case INTERNAL   = 7;
    case SOLD       = 8;
    case SCRAPPED   = 9;
    case LOST       = 10;
    case BROKEN     = 11;
    case SHORTTERM  = 12;
    case EXTENDED   = 13;
    case PAIRED     = 14;
    case ALLOCATED = 15;
    case GRACE_PERIOD = 16;
    case UNKNOWN    = 99;

    public function getColor()
    {
        return match ($this) {
            self::AVAILABLE => 'secondary',
            self::STAGING => 'info',
            self::DELIVERY => 'warning',
            self::DEPLOYMENT => 'info',
            self::TERMINATION => 'warning',
            self::TERMINATED => 'light',
            self::ACTIVE => 'success',
            self::SOLD => 'danger',
            self::SCRAPPED => 'danger',
            self::LOST => 'danger',
            self::BROKEN => 'danger',
            self::SHORTTERM => 'info',
            self::EXTENDED => 'success',
            self::PAIRED => 'warning',
            self::INTERNAL => 'success',
            self::ALLOCATED => 'warning',
            self::GRACE_PERIOD => 'warning',
            self::UNKNOWN => 'dark',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::STAGING => 'Staging',
            self::DELIVERY => 'Delivery',
            self::DEPLOYMENT => 'Deployment',
            self::TERMINATION => 'Termination',
            self::TERMINATED => 'Terminated',
            self::ACTIVE => 'In Used',
            self::SOLD => 'Sold',
            self::SCRAPPED => 'Scrapped',
            self::LOST => 'Lost',
            self::BROKEN => 'Broken',
            self::SHORTTERM => 'Short Term',
            self::EXTENDED => 'Extend',
            self::PAIRED => 'Paired',
            self::INTERNAL => 'Internal Use',
            self::ALLOCATED => 'Allocated',
            self::GRACE_PERIOD => 'Grace Period',
            self::UNKNOWN => 'Unknown',
        };
    }

    function convertStatusLabelToEnum($label)
    {
        $label = strtolower(trim($label));
        foreach (UnitStatus::cases() as $status) {
            if (strtolower($status->getLabel()) === $label) {
                return $status;
            }
        }
        return UnitStatus::UNKNOWN;
    }
}
