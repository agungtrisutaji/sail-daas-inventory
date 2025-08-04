<?php

namespace App\Models;

use App\Enums\RequestUpgradeStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RequestUpgrade extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'ticket',
        'operational_unit_id',
        'operational_unit_address',
        'bast_date',
        'company_id',
        'offering_price',
        'expense_part',
        'expense_engineer',
        'expense_delivery',
        'expense_total',
        'status',
        'engineer',
    ];

    protected $casts = [
        'bast_date' => 'date',
        'status' => RequestUpgradeStatus::class,
    ];

    protected $appends = [
        'status_label',
        'status_color',
    ];

    public function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status->getLabel($this->status),
        );
    }

    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status->getColor($this->status),
        );
    }

    public function operationalUnit(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'operational_unit_id', 'id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket', 'ticket_number');
    }

    public function upgradeDetail(): HasOne
    {
        return $this->hasOne(UpgradeDetail::class);
    }
}
