<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FieldActivity;
use App\Notifications\ActivityReminderNotification;
use Carbon\Carbon;

class SendActivityReminders extends Command
{
    protected $signature = 'activities:send-reminders';
    protected $description = 'Send reminders for field activities with a reminder date of today';

    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $activities = FieldActivity::whereDate('reminder_date', $today)->get();
        $count = 0;
        foreach ($activities as $activity) {
            if ($activity->createdBy && $activity->createdBy->email) {
                $activity->createdBy->notify(new ActivityReminderNotification($activity));
                $count++;
            }
        }
        $this->info("Reminders sent for {$count} activities.");
    }
}
