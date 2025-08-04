<?php

namespace App\Console\Commands;

use App\Notifications\UnitEmailNotification;
use App\Enums\TerminationStatus;
use App\Enums\UnitStatus;
use App\Models\Deployment;
use App\Models\User;
use App\Models\Termination;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TerminationCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:termination-create-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create termination if unit contract is expired';

    /**
     * Execute the console command.
     */

    /**
     * TODO: Create return status fo return monitoring
     */
    public function handle()
    {
        $today = Carbon::today();

        $endContractUnits = Deployment::where(function ($query) use ($today) {
            $query->where('end_contract', '<=', $today->toDateString())
                ->where(['is_terminated' => false, 'is_extended' => false])
                ->whereHas('unit', function ($unitQuery) {
                    $unitQuery->where('status', UnitStatus::ACTIVE->value);
                });
        })->get();

        $endContractUnitSerials = $endContractUnits->pluck('unit.serial');

        Log::info("Found " . $endContractUnits->count() . " unit(s) with end contract date before today. " . $today->toDateString() . " - Unit serials: " . implode(', ', $endContractUnitSerials->toArray()));
        $updatedUnits = collect([]);
        $terminations = collect([]);
        $deployments = collect([]);

        foreach ($endContractUnits as $deployment) {

            try {
                DB::beginTransaction();
                $unit = $deployment->unit;

                $termination = Termination::create([
                    'terminated_id' => $deployment->id,
                    'status' => TerminationStatus::NEW->value,
                ]);

                $unit->status = UnitStatus::TERMINATION->value;
                $unit->save();

                $updatedUnits->push($unit);
                $terminations->push($termination);
                $deployments->push($deployment);

                Log::info("Unit with Serial Number " . $unit->serial . " status updated to '" . strtoupper($unit->status_label) . "' and Termination data created.");
                $this->info("Unit with Serial Number  {$unit->serial} status updated to '" . strtoupper($unit->status_label) . "' and Termination data created.");

                DB::commit();

                /**
                 * TODO: create ticket for termination
                 * if terminated only, create ticket on termination update state
                 * if renewal, create ticket on staging import
                 */
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error("Unit with ID {$unit->serial} failed to update." . $th->getMessage());
                Log::error($th);
                $this->error($th->getMessage());
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Unit with ID {$unit->serial} failed to update." . $e->getMessage());
                Log::error($e);
                $this->error($e->getMessage());
            }
        }

        Log::info("Updated Units:" . $updatedUnits . "");
        $this->info('Unit status update completed.');
        $this->sendNotifications($updatedUnits, $terminations, $deployments);
    }
    protected function sendNotifications(Collection $units, Collection $model, Collection $deployments)
    {
        $user = User::where('email', 'agung.aprian@macro-trend.com')->first();
        if ($model->count() > 0) {
            $user->notify(new UnitEmailNotification($units, $model, $deployments, 'termination_number'));
        }
    }
}
