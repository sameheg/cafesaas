<?php

namespace Modules\Billing\Services;

use Modules\Billing\Models\Invoice;

class DunningHandler
{
    public function handle(Invoice $invoice, int $attempts): string
    {
        if ($attempts >= 3) {
            return 'suspend';
        }

        return 'retry';
    }
}
