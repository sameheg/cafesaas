<?php

namespace App\Services\Integrations;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Support\Facades\Http;

class HttpShippingGateway implements ShippingGateway
{
    /**
     * @param  array<string,mixed>  $config
     */
    public function __construct(private array $config) {}

    public function dispatch(Order $order): array
    {
        $baseUrl = rtrim($this->config['base_url'] ?? '', '/');
        $apiKey = $this->config['api_key'] ?? '';

        $response = Http::withToken($apiKey)
            ->post($baseUrl.'/shipments', ['order_id' => $order->id]);

        return ['tracking_number' => $response->json('tracking_number')];
    }

    public function track(Shipment $shipment): ?string
    {
        $baseUrl = rtrim($this->config['base_url'] ?? '', '/');
        $apiKey = $this->config['api_key'] ?? '';

        $response = Http::withToken($apiKey)
            ->get($baseUrl.'/track/'.$shipment->tracking_number);

        return $response->json('status');
    }
}
