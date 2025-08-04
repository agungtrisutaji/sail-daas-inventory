<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryService extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['courier_id', 'name', 'code', 'is_active'];

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
