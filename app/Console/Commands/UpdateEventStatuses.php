<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\RequestModel; // Adjust namespace as per your application

class UpdateEventStatuses extends Command
{
    protected $signature = 'events:update-statuses';
    protected $description = 'Update event statuses based on current date and time';

    public function handle()
    {
        $eventData = RequestModel::select('id', 'EventStartDate', 'EventEndDate', 'EventStartTime', 'EventEndTime', 'Statusid')->get();
        $currentDateTime = now();

        foreach ($eventData as $item) {
            $startDate = Carbon::parse($item->EventStartDate . ' ' . $item->EventStartTime);
            $endDate = Carbon::parse($item->EventEndDate . ' ' . $item->EventEndTime);

            if ($currentDateTime < $startDate) {
                $statusId = 11; // ID for upcoming events
            } elseif ($currentDateTime >= $startDate && $currentDateTime <= $endDate) {
                $statusId = 1; // ID for ongoing events
            } elseif ($currentDateTime > $endDate) {
                $statusId = 6; // ID for completed events
            } else {
                $statusId = null; // Handle any edge cases
            }

            // Update the status directly in the database
            RequestModel::where('id', $item->id)->update(['Statusid' => $statusId]);
        }

        $this->info('Event statuses updated successfully.');
    }
}
