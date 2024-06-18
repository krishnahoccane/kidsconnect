<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RequestModel;
use Carbon\Carbon;

class UpdateEventStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:statusUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
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
        
        \Log::info('Event Status Updated Successfully');
    }
}
