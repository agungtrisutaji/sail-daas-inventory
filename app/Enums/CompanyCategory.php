<?php

namespace App\Enums;

use App\Traits\HasEnumQuery;
use App\Traits\HasOptions;

enum CompanyCategory: string
{
    use HasEnumQuery, HasOptions;

    case DISTRIBUTOR = 'Distributor';
    case SUPPLIER = 'Supplier';
    case MANUFACTURER = 'Manufacturer';
    case SERVICE_PROVIDER = 'ServiceProvider';
    case CUSTOMER = 'Customer';
    case OPERATIONAL = 'Operational';
    case OFFICE = 'Office';
    case OTHER = 'Other';

    public function getLabel(): string
    {
        return match ($this) {
            self::DISTRIBUTOR => 'Distributor',
            self::SUPPLIER => 'Supplier',
            self::MANUFACTURER => 'Manufacturer',
            self::SERVICE_PROVIDER => 'Service Provider',
            self::CUSTOMER => 'Customer',
            self::OPERATIONAL => 'Operational Unit',
            self::OFFICE => 'Office',
            self::OTHER => 'Other',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DISTRIBUTOR => 'success',
            self::SUPPLIER => 'success',
            self::MANUFACTURER => 'warning',
            self::SERVICE_PROVIDER => 'warning',
            self::CUSTOMER => 'info',
            self::OPERATIONAL => 'warning',
            self::OFFICE => 'primary',
            self::OTHER => 'secondary',
        };
    }
}
