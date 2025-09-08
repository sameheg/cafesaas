<?php

namespace App\Listeners;

use App\Events\OrderDelivered;
use App\Notifications\Channels\SmsChannel;
use App\Notifications\OrderDeliveredNotification;
use App\Support\ManagesIdempotency;
use Illuminate\Support\Facades\Notification;

class SendOrderDeliveredNotification
{
    use ManagesIdempotency;

    public function handle(OrderDelivered $event): void
    {
        $customer = $event->order->customer ?? null;
        if (! $customer) {
            return;
        }

        $this->once('order:delivered:'.$event->order->id, function () use ($customer, $event) {
            Notification::send($customer, new OrderDeliveredNotification($event->order, ['mail', SmsChannel::class]));
        });
    }
}
