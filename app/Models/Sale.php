<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sale extends Model
{
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'sale_items', 'sale_id', 'unit_serial', 'id', 'serial', 'unit_serial');
    }
}
