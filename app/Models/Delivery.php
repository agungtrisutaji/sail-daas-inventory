<?php

namespace App\Models;

use App\Enums\DeliveryStatus;
use App\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Delivery extends Model
{
    use HasFactory, HasUuids, HasDocumentNumber;

    protected $guarded = [];

    protected $fillable = [
        'id',
        'delivery_number',
        'delivery_date',
        'estimated_arrival_date',
        'actual_arrival_date',
        'status',
        'courier_id',
        'delivery_service_id',
        'tracking_number',
        'notes',
        'sla',
        'category',
        'company_id',
        'company_address',
    ];

    protected $casts = [
        'status' => DeliveryStatus::class
    ];

    protected $appends = [
        'status_label',
    ];

    public function getDocumentConfig(): array
    {
        return [
            'prefix' => 'DO-',
            'column' => 'delivery_number'
        ];
    }

    public function getStatusLabelAttribute()
    {
        return $this->status->getLabel();
    }

    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status->getColor($this->status),
        );
    }

    public function Company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'company_address', 'id');
    }

    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'delivery_units', 'delivery_id', 'unit_serial', 'id', 'serial', 'unit_serial');
    }

    public function deliveryService(): BelongsTo
    {
        return $this->belongsTo(DeliveryService::class);
    }
}
