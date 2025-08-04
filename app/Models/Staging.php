<?php

namespace App\Models;

use App\Enums\RequestCategory;
use App\Enums\StagingStatus;
use App\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Staging extends Model
{
    use HasFactory, HasUuids, HasDocumentNumber;

    protected $guarded = [];
    protected $dates = ['staging_start', 'staging_finish'];

    protected $fillable = [
        'id',
        'staging_number',
        'unit_serial',
        'service_code',
        'company_id',
        'request_code',
        'staging_start',
        'staging_finish',
        'staging_number',
        'sla',
        'status',
        'request_category',
        'staging_monitor',
        'operational_unit_id',
        'operational_unit_address',
        'holder_name',
        'company_address',
        'is_deployed',
        'delivery_id',
        'on_delivery',
        'termination_id',
        'batch_number',
    ];


    protected $casts = [
        'id' => 'string',
        'status' => StagingStatus::class,
        'request_category' => RequestCategory::class,
        'terminated_serial' => 'string',
    ];

    protected $appends = [
        'status_label',
        'request_category_label',
        // 'terminated_serial',
    ];

    public static function generateStagingNumber()
    {
        $latestStaging = static::latest()->first();
        $nextNumber = $latestStaging ? (intval(substr($latestStaging->staging_number, -4)) + 1) : 1;
        return 'BSTG-' . date('ym') . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getDocumentConfig(): array
    {
        return [
            'prefix' => 'STG-',
            'column' => 'staging_number'
        ];
    }

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


    public function requestCategoryLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->request_category ? $this->request_category->getLabel($this->request_category) : '-',
        );
    }

    protected function requestCategoryColor(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->request_category->getColor($this->request_category),
        );
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_serial', 'serial');
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'staging_monitor', 'serial');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_code', 'code');
    }

    public function serviceName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->service ? $this->service->label : '-',
        );
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function operationalUnit(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'operational_unit_id', 'id');
    }

    public function deliveries(): BelongsToMany
    {
        return $this->belongsToMany(Delivery::class, 'delivery_stagings', 'staging_id', 'delivery_id', 'id', 'id', 'delivery_id');
    }

    public function deployment(): HasOne
    {
        return $this->hasOne(Deployment::class);
    }

    public function companyAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'company_address');
    }

    public function addressLocation(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->companyAddress ? $this->companyAddress->location : null,
        );
    }


    public function operationalAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'operational_unit_address');
    }

    public function termination(): BelongsTo
    {
        return $this->belongsTo(Termination::class, 'termination_id', 'id');
    }

    public function terminatedSerial(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->termination ? $this->termination->terminated_serial : null,
        );
    }
}
