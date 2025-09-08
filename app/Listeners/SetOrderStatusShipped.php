<?php

namespace App\Listeners;

use App\Events\OrderShipped;

class SetOrderStatusShipped
{
    public function handle(OrderShipped $event): void
    {
        $event->order->update(['status' => 'shipped']);
    }
}
