<?php

namespace App\Listeners;

use App\Events\PaymentProcessed;
use App\Services\NotificationService;
use App\Support\ManagesIdempotency;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderPaidNotification implements ShouldQueue
{
    use ManagesIdempotency;

    public function __construct(private NotificationService $notifications) {}

    public function handle(PaymentProcessed $event): void
    {
        $this->once('order:paid:'.$event->payment->id, function () use ($event) {
            if ($event->order->relationLoaded('customer') || $event->order->customer) {
                $this->notifications->send($event->order->customer, 'order.paid', ['mail', 'sms']);
            }
        });
    }
}
