<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum DeploymentStatus: int
{
    use HasOptions, HasEnumQuery;


    case ON_DELIVERY = 0;
    case PROCESSING = 1;
    case PLANNING = 2;
    case FOLLOW_UP = 3;
    case WAITING_CLOSE = 4;
    case COMPLETED = 5;

    public function getColor()
    {
        return match ($this) {
            self::PROCESSING => 'primary',
            self::PLANNING => 'info',
            self::FOLLOW_UP => 'success',
            self::WAITING_CLOSE => 'warning',
            self::COMPLETED => 'success',
            self::ON_DELIVERY => 'warning',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::PROCESSING => 'In Progress',
            self::PLANNING => 'Planning',
            self::FOLLOW_UP => 'Follow up',
            self::WAITING_CLOSE => 'Waiting Close',
            self::COMPLETED => 'Completed',
            self::ON_DELIVERY => 'On Delivery',
        };
    }
}
