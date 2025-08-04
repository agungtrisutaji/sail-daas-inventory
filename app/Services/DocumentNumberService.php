<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocumentNumberService
{
    private string $prefix;
    private string $dateFormat;
    private int $numberPadding;
    private string $numberColumn;

    public function __construct(
        string $prefix,
        string $numberColumn,
        string $dateFormat = 'ym',
        int $numberPadding = 4
    ) {
        $this->prefix = $prefix;
        $this->dateFormat = $dateFormat;
        $this->numberPadding = $numberPadding;
        $this->numberColumn = $numberColumn;
    }

    public function generate(Model $model): string
    {
        return DB::transaction(function () use ($model) {
            $latestRecord = $model::lockForUpdate()
                ->latest($this->numberColumn)
                ->first();

            $nextNumber = $latestRecord
                ? (intval(substr($latestRecord->{$this->numberColumn}, -$this->numberPadding)) + 1)
                : 1;

            return $this->prefix .
                date($this->dateFormat) .
                str_pad($nextNumber, $this->numberPadding, '0', STR_PAD_LEFT);
        });
    }
}
