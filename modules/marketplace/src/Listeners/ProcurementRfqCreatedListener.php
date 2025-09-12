<?php

namespace Modules\Marketplace\Listeners;

use Illuminate\Support\Facades\Log;

class ProcurementRfqCreatedListener
{
    public function handle(array $payload): void
    {
        Log::info('RFQ created', $payload);
    }
}
