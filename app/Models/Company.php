<?php

namespace App\Models;

use App\Enums\CompanyGroup;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['id', 'company_name', 'company_group', 'company_code', 'company_category'];

    protected $casts = [
        'company_group' => CompanyGroup::class,
    ];
    protected $guarded = [];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function addressName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->address->pluck('location')->join(', ')
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, "parent_id");
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, "parent_id");
    }

    public function stagings(): HasMany
    {
        return $this->hasMany(Staging::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'distributor_id');
    }
}
