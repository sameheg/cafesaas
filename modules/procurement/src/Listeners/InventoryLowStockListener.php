<?php

namespace Modules\Procurement\Listeners;

use Illuminate\Support\Facades\Log;

class InventoryLowStockListener
{
    public function handle(array $payload): void
    {
        Log::info('inventory.low.stock@v1 consumed', $payload);
    }
}
