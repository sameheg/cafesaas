<?php

declare(strict_types=1);

namespace Modules\Crm\Listeners;

use Illuminate\Support\Facades\Cache;
use Modules\Crm\Models\Customer;
use Modules\Crm\Services\RfmCalculator;

class UpdateRfmOnOrderPaid
{
    public function __construct(private RfmCalculator $calculator)
    {
    }

    /**
     * Handle the event payload from pos.order.paid@v1.
     */
    public function handle(array $payload): void
    {
        $customerId = $payload['customer_id'] ?? null;
        if (! $customerId) {
            return;
        }

        $key = 'pos.order.paid:'.$customerId.':'.($payload['idempotency_key'] ?? '');
        if (! Cache::add($key, true, now()->addMinutes(30))) {
            return; // idempotency
        }

        $customer = Customer::find($customerId);
        if (! $customer) {
            return;
        }

        $score = $this->calculator->calculate(
            recency: 0,
            frequency: ($payload['frequency'] ?? 1),
            monetary: (float) ($payload['total'] ?? 0)
        );

        $customer->rfm_score = $score;
        $customer->save();
    }
}
