<?php

namespace App\Notifications;

use App\Models\Extend;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UnitEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $units;
    protected $models;
    protected $deployments;
    protected $param;

    /**
     * Create a new notification instance.
     */
    public function __construct(Collection $units, Collection $models, Collection $deployments, string $param = 'id')
    {
        $this->units = $units;
        $this->models = $models;
        $this->deployments = $deployments;
        $this->id = Str::orderedUuid();
        $this->param = $param;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        try {
            $hasUnits = $this->units->count() > 0;

            return (new MailMessage)
                ->subject('no-reply: Unit Status Updated' . ($hasUnits ? ' to ' . $this->units->first()->status_label : ''))
                ->view('emails.unit-notification', [
                    'user' => $notifiable,
                    'hasUnits' => $hasUnits,
                    'modelName' => $hasUnits ? $this->units->first()->status_label : null,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i'),
                    'units' => $hasUnits ? $this->units->pluck('serial')->toArray() : [],
                    'deploymentIds' => $hasUnits ? $this->deployments->pluck('deployment_number')->toArray() : [],
                    'modelIds' => $hasUnits ? $this->models->pluck($this->param)->toArray() : [],
                    'actionUrl' => route('inventory')
                ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in toMail method: ' . $e->getMessage());

            // Return a fallback mail message
            return (new MailMessage)
                ->subject('Notification Error')
                ->line('There was an error generating the notification details.');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable): array
    {
        $data = [];
        foreach ($this->units as $key => $unit) {
            $data[] = [
                'unit_id' => $unit->id,
                'unit_serial' => $unit->serial,
                'model_id' => $this->models[$key]->id,
                'message' => "Unit {$unit->serial} has been updated to Extended status"
            ];
        }
        Log::info($data);
        return $data;
    }
    public function toBroadcast($notifiable): BroadcastMessage
    {
        $broadcastData = [];
        foreach ($this->units as $key => $unit) {
            $broadcastData[] = [
                'unit_id' => $unit->id,
                'unit_serial' => $unit->serial,
                'model_id' => $this->models[$key]->id,
                'message' => "Unit {$unit->serial} has been updated to Extended status"
            ];
        }

        return new BroadcastMessage($broadcastData);
    }
}
