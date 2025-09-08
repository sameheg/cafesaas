<?php

namespace App\Notifications;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleReminder extends Notification
{
    use Queueable;

    public function __construct(private Schedule $schedule) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Schedule Reminder')
            ->line('Upcoming: '.$this->schedule->title)
            ->line('Starts at: '.$this->schedule->starts_at->toDayDateTimeString());
    }
}
