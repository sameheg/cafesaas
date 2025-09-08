<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use App\Notifications\Channels\SmsChannel;
use App\Notifications\OrderShippedNotification;
use App\Support\ManagesIdempotency;
use Illuminate\Support\Facades\Notification;

class SendOrderShippedNotification
{
    use ManagesIdempotency;

    public function handle(OrderShipped $event): void
    {
        $customer = $event->order->customer ?? null;
        if (! $customer) {
            return;
        }

        $this->once('order:shipped:'.$event->order->id, function () use ($customer, $event) {
            Notification::send($customer, new OrderShippedNotification($event->order, ['mail', SmsChannel::class]));
        });
    }
}
