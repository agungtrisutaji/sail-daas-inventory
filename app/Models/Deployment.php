<?php

namespace App\Models;

use App\Enums\DeploymentStatus;
use App\Enums\RequestCategory;
use App\Traits\HasContractDuration;
use App\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deployment extends Model
{
    use HasFactory, HasUuids, HasContractDuration, HasDocumentNumber;

    protected $fillable = [
        'id',
        'staging_id',
        'company_id',
        'company_address',
        'deployment_date',
        'status',
        'deployment_note',
        'bast_number',
        'ritm_number',
        'bast_date',
        'bast_sign_date',
        'approved_by',
        'sla',
        'minimum_agreed_service_period',
        'service_period_years',
        'service_period_months',
        'unit_serial',
        'delivery_id',
        'end_contract',
        'holder_name',
        'is_terminated',
        'is_extended',
        'end_grass_period',
    ];

    protected $dates = [
        'bast_date',
        'end_contract'
    ];

    protected $casts = [
        'id' => 'string',
        'status' => DeploymentStatus::class,
        'request_category' => RequestCategory::class,
    ];

    public function getDocumentConfig(): array
    {
        return [
            'prefix' => 'DPL-',
            'column' => 'deployment_number'
        ];
    }

    public function requestCategoryLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->request_category ? $this->request_category->getLabel($this->request_category) : '-',
        );
    }

    public function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status->getLabel($this->status),
        );
    }

    public function staging(): BelongsTo
    {
        return $this->belongsTo(Staging::class);
    }

    /**
     * Get the company that owns the Deployment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_serial', 'serial');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'company_address', 'id');
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function terminatedUnits(): Collection
    {
        return $this->where('terminated', true)->get();
    }

    public function terminatedSerial(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->staging ? $this->staging->terminated_serial : null,
        );
    }

    public function companyName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->staging ? $this->staging->company->company_name : null,
        );
    }

    public function companyLocation(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->staging ? $this->staging->address_location : null,
        );
    }
}
