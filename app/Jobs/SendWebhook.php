<?php

namespace App\Jobs;

use App\Models\WebhookKey;
use App\Models\WebhookLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private WebhookLog $log, private int $attempt = 1, private int $maxAttempts = 5) {}

    public function handle(): void
    {
        $key = WebhookKey::where('tenant_id', $this->log->tenant_id)
            ->where('service', $this->log->service)
            ->where('active', true)
            ->latest()
            ->first();

        if (! $key) {
            $this->log->update([
                'status' => 'failed',
                'attempts' => $this->attempt,
                'last_error' => 'missing_key',
            ]);

            return;
        }

        $payload = $this->log->payload;
        $signature = hash_hmac('sha256', json_encode($payload), $key->key);

        try {
            $response = Http::withHeaders([
                'X-Signature' => $signature,
            ])->post($this->log->url, $payload);

            $this->log->update([
                'headers' => ['X-Signature' => $signature],
                'response_code' => $response->status(),
                'response_body' => $response->body(),
                'attempts' => $this->attempt,
                'status' => $response->successful() ? 'success' : 'failed',
                'last_error' => $response->successful() ? null : $response->body(),
            ]);

            if (! $response->successful()) {
                $this->retry();
            }
        } catch (\Throwable $e) {
            $this->log->update([
                'attempts' => $this->attempt,
                'status' => 'failed',
                'last_error' => $e->getMessage(),
            ]);
            $this->retry();
        }
    }

    private function retry(): void
    {
        if ($this->attempt < $this->maxAttempts) {
            $delay = $this->attempt * 2;
            self::dispatch($this->log, $this->attempt + 1, $this->maxAttempts)->delay($delay);
            Log::warning('webhook.retry', ['log_id' => $this->log->id, 'attempt' => $this->attempt + 1]);
        } else {
            Log::error('webhook.failed', ['log_id' => $this->log->id]);
        }
    }
}
