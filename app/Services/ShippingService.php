<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Shipment;
use App\Services\Integrations\ShippingGatewayFactory;

class ShippingService
{
    public function __construct(private ShippingGatewayFactory $factory) {}

    public function dispatch(Order $order, string $provider): Shipment
    {
        $gateway = $this->factory->make($provider, $order->tenant_id);
        $result = $gateway->dispatch($order);

        return Shipment::create([
            'tenant_id' => $order->tenant_id,
            'order_id' => $order->id,
            'tracking_number' => $result['tracking_number'] ?? '',
            'status' => 'pending',
            'provider' => $provider,
        ]);
    }

    public function refresh(Shipment $shipment): void
    {
        $gateway = $this->factory->make($shipment->provider, $shipment->tenant_id);

        if ($status = $gateway->track($shipment)) {
            $shipment->status = $status;
            $shipment->save();
        }
    }
}
