<?php

namespace App\Services;

use App\Models\IntegrationConfig;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Support\Facades\Http;

class ShippingService
{
    public function dispatch(Order $order, string $provider): Shipment
    {
        $config = IntegrationConfig::where('tenant_id', $order->tenant_id)
            ->where('service', $provider)
            ->firstOrFail();

        $baseUrl = rtrim(data_get($config->config_json, 'base_url', ''), '/');
        $apiKey = data_get($config->config_json, 'api_key');

        $response = Http::withToken($apiKey)
            ->post($baseUrl.'/shipments', ['order_id' => $order->id]);

        $tracking = $response->json('tracking_number');

        return Shipment::create([
            'tenant_id' => $order->tenant_id,
            'order_id' => $order->id,
            'tracking_number' => $tracking,
            'status' => 'pending',
            'provider' => $provider,
        ]);
    }

    public function refresh(Shipment $shipment): void
    {
        $config = IntegrationConfig::where('tenant_id', $shipment->tenant_id)
            ->where('service', $shipment->provider)
            ->first();

        if (! $config) {
            return;
        }

        $baseUrl = rtrim(data_get($config->config_json, 'base_url', ''), '/');
        $apiKey = data_get($config->config_json, 'api_key');

        $response = Http::withToken($apiKey)
            ->get($baseUrl.'/track/'.$shipment->tracking_number);

        if ($status = $response->json('status')) {
            $shipment->status = $status;
            $shipment->save();
        }
    }
}
