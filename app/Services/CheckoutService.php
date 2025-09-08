<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Integrations\PaymentGatewayFactory;

class CheckoutService
{
    public function __construct(private PaymentGatewayFactory $gateways) {}

    /**
     * @param  array<string,mixed>  $address
     */
    public function setAddress(int $tenantId, array $address): void
    {
        // Address handling would persist per-tenant checkout session.
    }

    /**
     * @param  array<string,mixed>  $details
     */
    public function setShipping(int $tenantId, array $details): void
    {
        // Shipping selection persistence placeholder.
    }

    /**
     * @param  array<string,mixed>  $paymentDetails
     */
    public function processPayment(int $tenantId, Order $order, string $provider, array $paymentDetails): Payment
    {
        $gateway = $this->gateways->make($provider, $tenantId);

        $result = $gateway->charge($order, $paymentDetails);

        return Payment::create([
            'tenant_id' => $tenantId,
            'order_id' => $order->id,
            'subscription_id' => 0,
            'amount_cents' => $order->total_cents,
            'currency' => $paymentDetails['currency'] ?? 'USD',
            'provider' => $provider,
            'reference' => $result['id'] ?? null,
            'status' => $result['status'] ?? 'unknown',
            'result' => $result,
        ]);
    }
}
