<?php

namespace App\Support;

class Orchestrator
{
    public function __construct(private EventBus $bus) {}

    public function dispatch(string $flow, array $payload = []): void
    {
        $this->bus->publish("flow.".$flow, $payload);
    }
}
