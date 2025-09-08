<?php

namespace App\Listeners;

use App\Events\OrderDelivered;
use App\Models\NotificationPreference;
use App\Notifications\OrderDeliveredNotification;

class SendOrderDeliveredNotification
{
    public function handle(OrderDelivered $event): void
    {
        $customer = $event->order->customer ?? null;
        if (! $customer) {
            return;
        }

        $channels = NotificationPreference::where('tenant_id', $event->order->tenant_id)
            ->where('template_key', 'order.delivered')
            ->where('enabled', true)
            ->pluck('channel')
            ->toArray();

        if (empty($channels)) {
            return;
        }

        $customer->notify(new OrderDeliveredNotification($event->order, $channels));
    }
}
