<?php

namespace App\Models;

use App\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Termination extends Model
{
    use HasFactory, HasUuids, HasDocumentNumber;

    protected $fillable = [
        'id',
        'status',
        'ticket_id',
        'ticket_jarvis',
        'holder_name',
        'sn_renewal',
        'company_id',
        'company_address',
        'termination_company_id',
        'termination_company_address',
        'termination_type',
        'termination_remark',
        'deployment_id',
        'renewal_date',
        'end_contract_date',
        'terminated_id',
        'termination_date',
    ];

    protected $casts = [
        'terminated_serial' => 'string',
        'renewal_serial' => 'string',
        // 'ticket_number' => 'string',
        // 'ticket_jarvis' => 'string',
    ];
    protected $appends = [
        'terminated_serial',
        'renewal_serial',
        // 'ticket_number',
        // 'ticket_jarvis',
    ];

    public function getDocumentConfig(): array
    {
        return [
            'prefix' => 'TRM-',
            'column' => 'termination_number'
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function renewal(): HasOne
    {
        return $this->hasOne(Staging::class, 'termination_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function terminated(): BelongsTo
    {
        return $this->belongsTo(Deployment::class, 'terminated_id', 'id');
    }

    public function unit()
    {
        return $this->terminated->unit;
    }

    public function getTerminatedSerialAttribute()
    {
        return $this->terminated->unit_serial;
    }

    public function getRenewalSerialAttribute()
    {
        return $this->renewal->unit_serial ?? null;
    }

    // public function getTicketNumberAttribute()
    // {
    //     return $this->ticket->ticket_number ?? null;
    // }
    // public function getTicketJarvisAttribute()
    // {
    //     return $this->ticket->ticket_jarvis ?? null;
    // }
}
