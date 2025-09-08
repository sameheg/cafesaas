<?php

namespace App\Services\Integrations;

use App\Models\IntegrationConfig;

class ShippingGatewayFactory
{
    public function make(string $provider, int $tenantId): ShippingGateway
    {
        $config = IntegrationConfig::where('tenant_id', $tenantId)
            ->where('service', $provider)
            ->first();

        $cfg = $config?->config_json ?? [];

        return new HttpShippingGateway($cfg);
    }
}
