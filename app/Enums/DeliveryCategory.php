<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum DeliveryCategory: int
{
    use HasOptions, HasEnumQuery;

    case GENERAL = 0;
    case STAGING = 1;
    case BACKUP = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::GENERAL => 'General',
            self::STAGING => 'Staging',
            self::BACKUP => 'Backup',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::GENERAL => 'primary',
            self::STAGING => 'info',
            self::BACKUP => 'success',
        };
    }

    public function getString(): string
    {
        return match ($this) {
            self::GENERAL => 'general',
            self::STAGING => 'staging',
            self::BACKUP => 'backup',
        };
    }

    public static function getValue(string $value): self
    {
        return match ($value) {
            'general' => self::GENERAL,
            'staging' => self::STAGING,
            'backup' => self::BACKUP,
        };
    }
}
