<?php

namespace App\Services\Integrations;

use App\Models\Order;

class StripeGateway implements PaymentGateway
{
    public function __construct(private array $config) {}

    /**
     * @param  array<string,mixed>  $details
     * @return array<string,mixed>
     */
    public function charge(Order $order, array $details): array
    {
        // Simulated charge. Real implementation would use Stripe SDK.
        return [
            'id' => 'stripe_'.uniqid(),
            'status' => 'succeeded',
        ];
    }
}
