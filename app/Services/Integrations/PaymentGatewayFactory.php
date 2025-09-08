<?php

namespace App\Services\Integrations;

use App\Models\IntegrationConfig;
use RuntimeException;

class PaymentGatewayFactory
{
    public function make(string $provider, int $tenantId): PaymentGateway
    {
        $config = IntegrationConfig::where('tenant_id', $tenantId)
            ->where('service', $provider)
            ->first();

        $cfg = $config?->config_json ?? [];

        return match ($provider) {
            'stripe' => new StripeGateway($cfg),
            'paypal' => new PayPalGateway($cfg),
            default => throw new RuntimeException("Unsupported payment provider: {$provider}"),
        };
    }
}
