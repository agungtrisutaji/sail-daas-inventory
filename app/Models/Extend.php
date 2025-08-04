<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extend extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'deployment_id',
        'unit_serial',
    ];

    public function deployment()
    {
        return $this->belongsTo(Deployment::class);
    }
}
