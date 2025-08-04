<?php

namespace App\Enums;

enum ServiceCenterCategory: string
{
    case DAAS = 'DAAS';
    case EXTEND = 'EXTEND';

    public function getColor(): string
    {
        return match ($this) {
            self::DAAS => 'primary',
            self::EXTEND => 'success',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::DAAS => 'DaaS',
            self::EXTEND => 'Extend and Short Term',
        };
    }
}
