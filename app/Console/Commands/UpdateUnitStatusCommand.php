<?php

namespace App\Console\Commands;

use App\Enums\TerminationStatus;
use App\Enums\UnitCategory;
use App\Enums\UnitStatus;
use App\Models\Deployment;
use App\Models\Extend;
use App\Models\Termination;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\UnitEmailNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateUnitStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-during-grace-period';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update unit status during the grace period .';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * TODO: update to extend from end_grace_period
         * cancle the termination befor creating extend
         **/
        $today = Carbon::today();

        $gracedUnits = Deployment::where(function ($query) {
            $query->where(['is_terminated' => false, 'is_extended' => false])
                ->whereHas('unit', function ($unitQuery) {
                    $unitQuery->where('status', UnitStatus::ACTIVE->value);
                });
        })->get();

        $gracedUnitSerials = $gracedUnits->pluck('unit.serial');

        Log::info("Found " . $gracedUnits->count() . " unit(s) with end contract date before today. " . $today->toDateString() . " - Unit serials: " . implode(', ', $gracedUnitSerials->toArray()));

        $updatedExtendUnits = collect([]);
        $updatedTerminateUnits = collect([]);
        $extends = collect([]);
        $terminations = collect([]);
        $deploymentToExtends = collect([]);
        $deploymentToTerminates = collect([]);

        foreach ($gracedUnits as $deployment) {
            try {
                DB::beginTransaction();
                // $deployment = $deployment->deployments()->where('end_grace_period', '<=', $today->toDateString())->first();
                $deploymentToExtend = $deployment->where('end_grace_period', '<=', $today->toDateString())->first();
                $deploymentToTerminate = $deployment->where('end_contract', '<=', $today->toDateString())->whereNot('end_grace_period', '<=', $today->toDateString())->first();

                if ($deploymentToTerminate) {

                    $termination = new Termination();
                    $termination->terminated_id = $deploymentToTerminate->id;
                    $termination->status = TerminationStatus::NEW->value;
                    $termination->save();

                    $terminations->push($termination);
                    $deploymentToTerminates->push($deploymentToTerminate);
                    $updatedTerminateUnits->push($deploymentToTerminate->unit);

                    $deploymentToTerminate->unit->status = UnitStatus::EXTENDED->value;
                    $deploymentToTerminate->unit->save();
                } elseif ($deploymentToExtend) {
                    $extend = new Extend();
                    $extend->unit_serial = $deploymentToExtend->unit->serial;
                    $extend->deployment_id = $deploymentToExtend->id;
                    $extend->save();

                    $deploymentToExtend->is_extended = true;
                    $deploymentToExtend->save();

                    $extends->push($extend);
                    $deploymentToExtends->push($deploymentToExtend);
                    $updatedExtendUnits->push($deploymentToExtend->unit);

                    $deploymentToExtend->unit->status = UnitStatus::EXTENDED->value;
                    $deploymentToExtend->unit->save();
                } else {
                    Log::info('No extend or terminate found for unit with Serial Number ' . $deployment->unit->serial);
                }

                Log::info('Updated Extend Units: ' . $updatedExtendUnits . ' Updated Terminate Units: ' . $updatedTerminateUnits);

                Log::info('Extends: ' . $extends . ' Terminations: ' . $terminations);

                Log::info('Deployment To Extends: ' . $deploymentToExtends . ' Deployment To Terminates: ' . $deploymentToTerminates);

                Log::info("Unit with Serial Number " . $deployment->unit->serial . " status updated to '" . strtoupper($deployment->unit->status_label) . "' and Termination data created.");
                $this->info("Unit with Serial Number  {$deployment->unit->serial} status updated to '" . strtoupper($deployment->unit->status_label) . "' and Termination data created.");
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error("Unit with ID {$deployment->unit->serial} failed to update." . $th->getMessage());
                Log::error($th);
                $this->error($th->getMessage());
            }
        }
        Log::info("Updated Units:" . $updatedExtendUnits . "");
        $this->sendNotifications($updatedExtendUnits, $extends, $deploymentToExtends);
        $this->info('Unit status update completed.');
    }

    protected function sendNotifications(Collection $units, Collection $collection, Collection $deployments)
    {
        $user = User::where('email', 'agung.aprian@macro-trend.com')->first();
        $user->notify(new UnitEmailNotification($units, $collection, $deployments));
    }
}
