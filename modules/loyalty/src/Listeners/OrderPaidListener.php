<?php

namespace Modules\Loyalty\Listeners;

use Illuminate\Support\Facades\Cache;
use Modules\Loyalty\Services\LoyaltyService;

class OrderPaidListener
{
    public function __construct(protected LoyaltyService $service)
    {
    }

    public function handle(object $event): void
    {
        $orderId = $event->data['order_id'] ?? null;
        if ($orderId && ! Cache::add("loyalty:order:{$orderId}", true, 86400)) {
            return; // idempotency
        }

        $tenantId = $event->data['tenant_id'] ?? '';
        $customerId = $event->data['customer_id'] ?? '';
        $amount = (int) ($event->data['amount'] ?? 0);

        // Basic earn rate: 1 point per amount unit
        $points = $amount; 
        $this->service->earn($tenantId, $customerId, $points);
    }
}
