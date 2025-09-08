<?php

namespace App\Support;

use App\Jobs\DispatchDomainEvent;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;

class EventBus
{
    public function __construct(private Dispatcher $dispatcher) {}

    public function publish(string $event, array $payload = []): void
    {
        $envelope = [
            'id' => (string) Str::uuid(),
            'source' => config('app.url', ''),
            'type' => $event,
            'specversion' => '1.0',
            'time' => now()->toIso8601String(),
            'data' => $payload,
        ];

        DispatchDomainEvent::dispatch($event, $envelope);
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
