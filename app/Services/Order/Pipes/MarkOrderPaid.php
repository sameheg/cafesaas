<?php

namespace App\Services\Order\Pipes;

use App\Events\PaymentProcessed;
use App\Models\Order;
use App\Models\Payment;
use Closure;

class MarkOrderPaid
{
    public function handle(array $payload, Closure $next): mixed
    {
        /** @var Order $order */
        $order = $payload['order'];

        $payment = Payment::firstOrCreate(
            ['idempotency_key' => 'manual:'.$order->id],
            [
                'tenant_id' => $order->tenant_id,
                'order_id' => $order->id,
                'subscription_id' => 0,
                'amount_cents' => $order->total_cents,
                'currency' => 'USD',
                'provider' => 'manual',
                'reference' => 'manual-'.uniqid(),
                'status' => 'succeeded',
                'result' => [],
            ]
        );

        event(new PaymentProcessed($order, $payment));

        $order->status = 'paid';
        $order->save();

        return $next($payload);
    }
}
