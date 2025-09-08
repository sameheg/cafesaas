<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use App\Notifications\ScheduleReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendScheduleReminders extends Command
{
    protected $signature = 'schedules:send-reminders';

    protected $description = 'Send reminders for upcoming schedules';

    public function handle(): void
    {
        $now = Carbon::now();

        Schedule::where('reminder_sent', false)
            ->whereNotNull('reminder_minutes_before')
            ->get()
            ->filter(function (Schedule $schedule) use ($now) {
                return $schedule->starts_at
                    ->subMinutes($schedule->reminder_minutes_before)
                    ->lte($now);
            })
            ->each(function (Schedule $schedule) {
                Notification::send($schedule->user, new ScheduleReminder($schedule));
                $schedule->update(['reminder_sent' => true]);
            });
    }
}
