<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum StagingStatus: int
{
    use HasOptions, HasEnumQuery;

    case PROCESSING = 0;
    case COMPLETED = 1;
    case PENDING = 2;
    case CANCELLED = 3;
    case FAILED = 4;
    case DONE = 6;

    public function getColor()
    {
        return match ($this) {
            self::PROCESSING => 'primary',
            self::COMPLETED => 'success',
            self::PENDING => 'warning',
            self::CANCELLED => 'light',
            self::FAILED => 'danger',
            self::DONE => 'success',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::PENDING => 'Pending',
            self::CANCELLED => 'Cancelled',
            self::FAILED => 'Failed',
            self::DONE => 'Done',
        };
    }

    public function getNextStatus()
    {
        return match ($this) {
            self::PROCESSING => self::COMPLETED,
        };
    }
}
