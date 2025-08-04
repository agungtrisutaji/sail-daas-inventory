<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class SLAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureSla();
    }

    protected function configureSla(): void
    {
        $models = config('sla.models', []);

        foreach ($models as $modelClass => $config) {
            $modelClass::saving(function ($model) use ($config) {
                if ($model->isDirty($config['finish_column'])) {
                    $this->calculateSla($model, $config);
                }
            });
        }
    }

    protected function calculateSla(Model $model, array $config): void
    {
        $startDate = $this->getStartDate($model, $config);
        $finishDate = $model->{$config['finish_column']};

        if (is_null($startDate) || is_null($finishDate)) {
            $model->{$config['sla_column']} = null;
        } else {
            $start = Carbon::parse($startDate);
            $finish = Carbon::parse($finishDate);

            $diffInDays = $this->calculateDifference($start, $finish, $config);

            $sla = $diffInDays <= $config['sla_threshold'] ? 'Meet' : 'Breach';
            $model->updateQuietly([$config['sla_column'] => $sla]);
        }
    }

    protected function getStartDate(Model $model, array $config): ?string
    {
        if (isset($config['start_relation'])) {
            return $this->getRelatedStartDate($model, $config);
        }

        return $model->{$config['start_column']} ?? null;
    }

    protected function getRelatedStartDate(Model $model, array $config): ?string
    {
        $relationPath = explode('.', $config['start_relation']);
        $currentRelation = $model;

        foreach ($relationPath as $relation) {
            if (!$currentRelation->$relation) {
                return null;
            }
            $currentRelation = $currentRelation->$relation;
        }

        if ($currentRelation instanceof Collection) {
            return $currentRelation->min($config['start_column']);
        } elseif ($currentRelation instanceof Model) {
            return $currentRelation->{$config['start_column']};
        }

        return null;
    }

    protected function calculateWorkingDays(Carbon $start, Carbon $finish): int
    {
        $days = 0;
        $current = $start->copy();

        while ($current->lte($finish)) {
            if (!$current->isWeekend()) {
                $days++;
            }
            $current->addDay();
        }

        return $days;
    }
    protected function calculateDifference(Carbon $start, Carbon $finish, array $config): int
    {
        $method = $config['diff_method'] ?? 'diffInDays';

        if ($method === 'diffInDays') {
            return $this->calculateWorkingDays($start, $finish);
        }

        // Untuk metode lain, gunakan perhitungan default
        return $start->$method($finish);
    }
}
