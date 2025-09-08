<?php

namespace App\Jobs;

use App\Support\EventBus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class DispatchDomainEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $event,
        private array $payload = [],
        private int $attempt = 1,
        private int $maxAttempts = 3,
        private array $backoff = [1, 5, 10]
    ) {}

    public function handle(EventBus $bus): void
    {
        try {
            $bus->dispatchNow($this->event, $this->payload);
            Log::info('event.published', ['event' => $this->event, 'attempt' => $this->attempt]);
        } catch (\Throwable $e) {
            if ($this->attempt < $this->maxAttempts) {
                $delay = $this->backoff[$this->attempt - 1] ?? end($this->backoff);
                $this->attempt++;
                Log::warning('event.retry', [
                    'event' => $this->event,
                    'attempt' => $this->attempt,
                    'delay' => $delay,
                    'error' => $e->getMessage(),
                ]);
                $this->release($delay);
            } else {
                Redis::rpush('events:dlq', json_encode([
                    'event' => $this->event,
                    'payload' => $this->payload,
                    'error' => $e->getMessage(),
                ]));
                Log::error('event.dead_lettered', [
                    'event' => $this->event,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
