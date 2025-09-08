<?php

namespace App\Services;

use App\Jobs\SendNotification;

class NotificationService
{
    public function send(object $notifiable, string $templateKey, array $channels = ['mail']): void
    {
        SendNotification::dispatch(
            get_class($notifiable),
            $notifiable->getKey(),
            $templateKey,
            $channels
        );
    }
}
