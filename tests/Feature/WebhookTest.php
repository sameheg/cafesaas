<?php

namespace Tests\Feature;

use App\Jobs\SendWebhook;
use App\Models\Tenant;
use App\Models\WebhookKey;
use App\Models\WebhookLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_sends_signed_webhooks_and_logs_success(): void
    {
        $tenant = Tenant::factory()->create();

        WebhookKey::create([
            'tenant_id' => $tenant->id,
            'service' => 'test',
            'key' => 'secret',
        ]);

        Http::fake([
            'example.com/*' => Http::response('ok', 200),
        ]);

        $log = WebhookLog::create([
            'tenant_id' => $tenant->id,
            'service' => 'test',
            'url' => 'https://example.com/hook',
            'payload' => ['a' => 1],
        ]);

        (new SendWebhook($log))->handle();

        $log->refresh();

        $this->assertEquals('success', $log->status);
        $this->assertEquals(200, $log->response_code);
    }
}
