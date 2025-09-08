<?php

namespace App\Listeners;

use App\Events\OrderCreated;

class SetOrderStatusCreated
{
    public function handle(OrderCreated $event): void
    {
        $event->order->update(['status' => 'created']);
    }
}
