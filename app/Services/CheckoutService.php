<?php

namespace App\Services;

use App\Events\PaymentProcessed;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Integrations\PaymentGatewayFactory;
use InvalidArgumentException;

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
    public function processPayment(int $tenantId, Order $order, string $provider, array $paymentDetails, ?string $couponCode = null, ?string $idempotencyKey = null): Payment
    {
        if ($idempotencyKey && ($existing = Payment::where('idempotency_key', $idempotencyKey)->first())) {
            return $existing;
        }

        if ($couponCode) {
            $coupon = Coupon::where('tenant_id', $tenantId)
                ->where('code', $couponCode)
                ->first();

            if (! $coupon || ! $coupon->isValid()) {
                throw new InvalidArgumentException('Invalid coupon.');
            }

            $discount = $coupon->discountAmount($order->total_cents);
            $order->total_cents -= $discount;
            $order->save();

            if (! is_null($coupon->usage_limit)) {
                $coupon->decrement('usage_limit');
            }
        }

        $gateway = $this->gateways->make($provider, $tenantId);

        $result = $gateway->charge($order, $paymentDetails);

        $payment = Payment::create([
            'tenant_id' => $tenantId,
            'order_id' => $order->id,
            'subscription_id' => 0,
            'amount_cents' => $order->total_cents,
            'currency' => $paymentDetails['currency'] ?? 'USD',
            'provider' => $provider,
            'reference' => $result['id'] ?? null,
            'idempotency_key' => $idempotencyKey,
            'status' => $result['status'] ?? 'unknown',
            'result' => $result,
        ]);

        event(new PaymentProcessed($order, $payment));

        return $payment;
    }
}
