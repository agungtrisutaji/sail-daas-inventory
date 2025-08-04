<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum AssetTransferType: int
{
    use HasOptions, HasEnumQuery;

    case AFTER_DEPLOYMENT = 0;
    case INVENTORY_TRANSFER = 1;
    case OTHER = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::AFTER_DEPLOYMENT => 'After Deployment',
            self::INVENTORY_TRANSFER => 'Inventory Transfer',
            self::OTHER => 'Other',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::AFTER_DEPLOYMENT => 'success',
            self::INVENTORY_TRANSFER => 'info',
            self::OTHER => 'warning',
        };
    }
}
