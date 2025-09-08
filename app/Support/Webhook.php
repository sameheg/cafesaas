<?php

namespace App\Support;

use App\Jobs\SendWebhook;
use App\Models\WebhookLog;

class Webhook
{
    public static function send(int $tenantId, string $service, string $url, array $payload): WebhookLog
    {
        $log = WebhookLog::create([
            'tenant_id' => $tenantId,
            'service' => $service,
            'url' => $url,
            'payload' => $payload,
        ]);

        SendWebhook::dispatch($log);

        return $log;
    }
}
