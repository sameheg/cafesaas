<?php

namespace App\Services\Integrations;

use App\Models\Order;
use App\Models\Shipment;

interface ShippingGateway
{
    /**
     * Dispatch the given order and return provider response.
     *
     * @return array{tracking_number:string}
     */
    public function dispatch(Order $order): array;

    /**
     * Fetch latest status for the shipment.
     */
    public function track(Shipment $shipment): ?string;
}
