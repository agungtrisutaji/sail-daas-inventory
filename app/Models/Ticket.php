<?php

namespace App\Models;

use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory, HasUuids, HasDocumentNumber;

    protected $casts = [
        'status' => TicketStatus::class,
        'type' => TicketType::class,
    ];
    protected $guarded = [];
    protected $fillable = [
        'id',
        'ticket_number',
        'unit_serial',
        'company_id',
        'company_address',
        'caller',
        'requestor',
        'type',
        'status',
        'remarks',
        'jarvis_ticket',
        'service_tag',
        'request_date',
    ];

    protected $appends = [
        'status_label',
        'status_color',
        'type_label',
        'type_color',
    ];

    public function getDocumentConfig(): array
    {
        return [
            'prefix' => 'TC-',
            'column' => 'ticket_number'
        ];
    }

    public function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status->getLabel($this->status),
        );
    }
    public function statusColor(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status->getColor($this->status),
        );
    }
    public function statusBadge(): Attribute
    {
        return Attribute::make(
            get: fn(TicketStatus $status) => $status->status,

        );
    }

    public function typeLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->type->getLabel($this->type),
        );
    }


    public function typeColor(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->type->getColor($this->type),
        );
    }
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_serial', 'serial');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'company_address', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
