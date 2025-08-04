<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpgradeDetail extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'request_upgrade_id',
        'upgrade_type',
        'upgrade_size',
        'upgrade_remark',
    ];

    public function requestUpgrade(): BelongsTo
    {
        return $this->belongsTo(RequestUpgrade::class);
    }
}
