<?php

namespace App\Models;

use App\Enums\AssetTransferStatus;
use App\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetTransfer extends Model
{
    use HasFactory, HasUuids, HasDocumentNumber;

    protected $guarded = [];
    protected $fillable = [
        'unit_serial',
        'ticket_id',
        'ritm_number',
        'from_location_id',
        'to_location_id',
        'from_company_id',
        'to_company_id',
        'from_holder',
        'to_holder',
        'start_date',
        'finish_date',
        'operational_unit_id',
        'operational_unit_address',
        'qc_by',
        'qc_date',
        'qc_pass',
        'is_restaging',
        'document_availability',
        'transfer_for',
        'status',
        'transfer_remark',
    ];
    protected $casts = [
        'status' => AssetTransferStatus::class,
    ];

    public function getDocumentConfig(): array
    {
        return [
            'prefix' => 'AT-',
            'column' => 'transfer_number'
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_serial', 'serial');
    }

    public function deployment(): BelongsTo
    {
        return $this->belongsTo(Deployment::class, 'unit_serial', 'unit_serial');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function operationalUnit(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'operational_unit_id', 'id');
    }

    public function operationalLocation(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'operational_unit_address', 'id');
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'from_location_id', 'id');
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'to_location_id', 'id');
    }

    public function fromCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'from_company_id', 'id');
    }

    public function toCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'to_company_id', 'id');
    }
}
