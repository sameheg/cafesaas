<?php

namespace App\Services\Integrations;

use App\Models\IntegrationConfig;

class SmsGatewayFactory
{
    public function make(string $provider, int $tenantId): SmsGateway
    {
        $config = IntegrationConfig::where('tenant_id', $tenantId)
            ->where('service', $provider)
            ->first();

        $cfg = $config?->config_json ?? [];

        return match ($provider) {
            'twilio' => new TwilioGateway($cfg),
            default => new LogSmsGateway($cfg),
        };
    }
}
