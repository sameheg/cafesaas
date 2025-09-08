<?php

namespace App\Support;

use App\Jobs\DispatchDomainEvent;
use Illuminate\Contracts\Events\Dispatcher;

class EventBus
{
    public function __construct(private Dispatcher $dispatcher) {}

    public function publish(string $event, array $payload = []): void
    {
        DispatchDomainEvent::dispatch($event, $payload);
    }

    public function dispatchNow(string $event, array $payload = []): void
    {
        $this->dispatcher->dispatch($event, [$payload]);
    }

    public function subscribe(string $event, callable|string $listener): void
    {
        $this->dispatcher->listen($event, $listener);
    }
}
