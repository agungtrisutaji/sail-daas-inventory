<?php

namespace App\Models;

use App\Enums\StagingStatus;
use App\Enums\UnitCategory;
use App\Enums\UnitStatus;
use App\Exports\UnitsExport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    use HasFactory,  HasUuids;

    protected $keyType = 'string';
    public $incrementing = true;
    protected $guarded = [];

    protected $exportClass = UnitsExport::class;

    protected $fillable = [
        'id',
        'serial',
        'purchase_date',
        'receive_date',
        'receive_number',
        'brand',
        'model',
        'category',
        'note',
        'status',
        'distributor_id',
        'monitor',
        'is_backup',
        'asset_group',
        'alocated_for',
        'alocated_for_id',
    ];

    protected $casts = [
        'status' => UnitStatus::class,
        'category' => UnitCategory::class,
        'alocated_url' => 'string',
    ];

    protected $appends = [
        'status_label',
        'alocated_url',
        'distributor_name',
        'service_label',
    ];

    //TODO: UNIT: make fat model for service and staging

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

    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                $color = $this->status_color;
                $label = $this->status_label;
                return "<span class='badge bg-{$color}'>{$label}</span>";
            }
        );
    }

    public function monitor(): HasOne
    {
        return $this->hasOne(Unit::class, 'serial', 'monitor');
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'distributor_id', 'id');
    }

    public function getDistributorNameAttribute()
    {
        return $this->distributor ? $this->distributor->company_name : 'kosong';
    }

    public function stagings(): HasMany
    {
        return $this->hasMany(Staging::class, 'unit_serial', 'serial')->select('id', 'unit_serial', 'staging_monitor', 'staging_number', 'sla', 'holder_name', 'status', 'service_code', 'request_category', 'is_deployed', 'staging_finish');
    }

    public function latestStaging(): HasOne
    {
        return $this->hasOne(Staging::class, 'unit_serial', 'serial')->latestOfMany();
    }

    public function getServiceLabelAttribute()
    {
        $staging = $this->latestStaging;
        return $staging ? $staging->service->label : null;
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'unit_serial', 'serial');
    }

    public function deliveries(): BelongsToMany
    {
        return $this->belongsToMany(Delivery::class, 'delivery_units', 'unit_serial', 'delivery_id', 'serial', 'id', 'delivery_id');
    }

    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class, 'unit_serial', 'serial');
    }

    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class, 'unit_serial', 'serial');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($unit) {
            $unit->receive_number = static::generateReceiveNumber();
        });
    }

    public static function generateReceiveNumber()
    {
        $latestReceive = static::latest()->first();
        $nextNumber = $latestReceive ? (intval(substr($latestReceive->receive_number, -4)) + 1) : 1;
        return 'INV-' . date('ym') . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function currentSpecification(): HasOne
    {
        return $this->hasOne(Specification::class, 'id', 'specification');
    }

    public function getAlocatedUrlAttribute()
    {
        if ($this->alocated_for == UnitStatus::DEPLOYMENT->value) {
            return route('deployment.show', $this->alocated_for_id);
        } elseif ($this->alocated_for == UnitStatus::STAGING->value) {
            return route('staging.show', $this->alocated_for_id);
        } else {
            return null;
        }
    }

    public function serviceName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->stagings()->first() ? $this->stagings()->first()->service_name : null,
        );
    }
}
