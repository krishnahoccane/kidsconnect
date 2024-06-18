<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RequestModel;
use Carbon\Carbon;

class UpdateEventStatuses extends Command
{
    protected $signature = 'events:update-statuses';
    protected $description = 'Update event statuses based on current date and time';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Fetch all event data
        $eventData = RequestModel::select('id', 'EventStartDate', 'EventEndDate', 'EventStartTime', 'EventEndTime', 'Statusid')->get();

        // Define current date and time
        $currentDateTime = Carbon::now();

        // Iterate through each event to determine its status
        $eventData->each(function ($item) use ($currentDateTime) {
            // Convert event start date/time to DateTime objects
            $startDate = Carbon::parse($item->EventStartDate . ' ' . $item->EventStartTime);
            $endDate = Carbon::parse($item->EventEndDate . ' ' . $item->EventEndTime);

            // Determine event status based on current date/time
            if ($currentDateTime < $startDate) {
                $item->update(['Statusid' => 11]);
            } elseif ($currentDateTime >= $startDate && $currentDateTime <= $endDate) {
                $item->update(['Statusid' => 1]);
            } elseif ($currentDateTime > $endDate) {
                $item->update(['Statusid' => 6]);
            } else {
                $item->update(['Statusid' => 3]);
            }
        });

        $this->info('Event statuses have been updated successfully.');
    }
}
