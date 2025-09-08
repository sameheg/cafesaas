<?php

namespace App\Services\Integrations;

use App\Models\Order;

interface PaymentGateway
{
    /**
     * Charge the given order using provider specific details.
     *
     * @param  array<string,mixed>  $details
     * @return array<string,mixed> Result from provider
     */
    public function charge(Order $order, array $details): array;
}
