<?php

namespace App\Traits;

use App\Services\ContractService;
use Carbon\Carbon;

trait HasContractDuration
{
    protected static function bootHasContractDuration()
    {
        static::saving(function ($model) {
            if (!empty($model->bast_date)) {
                $contractService = new ContractService();
                $model->end_contract = $contractService->calculateEndContractDate(
                    $model->unit->category->value,
                    $model->bast_date
                );
                //grace period =  end contract + 14 days
                $model->end_grace_period = Carbon::parse($model->end_contract)->addDays(14);
            }

            if (empty($model->bast_date)) {
                $model->end_contract = null;
            }
        });
    }
}
