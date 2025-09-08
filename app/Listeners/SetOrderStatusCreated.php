<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Support\ManagesIdempotency;

class SetOrderStatusCreated
{
    use ManagesIdempotency;

    public function handle(OrderCreated $event): void
    {
        $this->once('order:status_created:'.$event->order->id, function () use ($event) {
            $event->order->update(['status' => 'created']);
        });
    }
}
