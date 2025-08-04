<?php

namespace App\Enums;

enum AssetGroup: int
{
    case DAAS = 1;
    case BREAKFIX = 2;
    case BACKUP = 3;
    case INTERNAL = 4;

    public function getLabel()
    {
        return match ($this) {
            self::DAAS => 'DAAS',
            self::BREAKFIX => 'Breakfix',
            self::BACKUP => 'Backup',
            self::INTERNAL => 'Internal Use',
        };
    }

    public function getColor()
    {
        return match ($this) {
            self::DAAS => 'primary',
            self::BREAKFIX => 'success',
            self::BACKUP => 'warning',
            self::INTERNAL => 'info',
        };
    }
}
