<?php

namespace App\Listeners;

use App\Events\PaymentProcessed;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderPaidNotification implements ShouldQueue
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(PaymentProcessed $event): void
    {
        if ($event->order->relationLoaded('customer') || $event->order->customer) {
            $this->notifications->send($event->order->customer, 'order.paid', ['mail', 'sms']);
        }
    }
}
