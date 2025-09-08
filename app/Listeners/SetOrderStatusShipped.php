<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use App\Support\ManagesIdempotency;

class SetOrderStatusShipped
{
    use ManagesIdempotency;

    public function handle(OrderShipped $event): void
    {
        $this->once('order:status_shipped:'.$event->order->id, function () use ($event) {
            if ($event->order->exists) {
                $event->order->update(['status' => 'shipped']);
            } else {
                $event->order->status = 'shipped';
            }
        });
    }
}
