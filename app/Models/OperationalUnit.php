<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperationalUnit extends Model
{
    use HasFactory, HasUuids;


    protected $keyType = 'string';
    public $incrementing = true;
    protected $guarded = [];


    protected $fillable = [
        'id',
        'name',
        'code',
        'description',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function stagings()
    {
        return $this->hasMany(Staging::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
