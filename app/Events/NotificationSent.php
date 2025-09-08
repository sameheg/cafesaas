<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public mixed $notifiable,
        public string $templateKey,
        public array $channels
    ) {}
}
