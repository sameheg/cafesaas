<?php

namespace App\Support;

use Illuminate\Contracts\Events\Dispatcher;

class EventBus
{
    public function __construct(private Dispatcher $dispatcher) {}

    public function publish(string $event, array $payload = []): void
    {
        $this->dispatcher->dispatch($event, $payload);
    }

    public function subscribe(string $event, callable|string $listener): void
    {
        $this->dispatcher->listen($event, $listener);
    }
}
