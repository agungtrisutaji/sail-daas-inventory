<?php

namespace App\Models;

use App\Enums\ServiceCategory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];
    protected $casts = [
        'service_category' => ServiceCategory::class,
    ];
    protected $appends = [
        'service_category_label',
    ];

    public function serviceCategoryLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->service_category->getLabel(),
        );
    }
    public function stagings(): HasMany
    {
        return $this->hasMany(Staging::class);
    }
}
