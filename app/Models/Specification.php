<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'unit_serial',
        'ram',
        'cpu',
        'storage',
        'display',
        'os',
        'vga',
        'battery',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_serial', 'serial');
    }
}
