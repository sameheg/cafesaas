<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use App\Models\NotificationPreference;
use App\Notifications\OrderShippedNotification;

class SendOrderShippedNotification
{
    public function handle(OrderShipped $event): void
    {
        $customer = $event->order->customer ?? null;
        if (! $customer) {
            return;
        }

        $channels = NotificationPreference::where('tenant_id', $event->order->tenant_id)
            ->where('template_key', 'order.shipped')
            ->where('enabled', true)
            ->pluck('channel')
            ->toArray();

        if (empty($channels)) {
            return;
        }

        $customer->notify(new OrderShippedNotification($event->order, $channels));
    }
}
