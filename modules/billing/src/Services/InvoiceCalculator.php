<?php

namespace Modules\Billing\Services;

class InvoiceCalculator
{
    public function calculate(array $modules, bool $proration = false): float
    {
        return collect($modules)->sum(function ($module) use ($proration) {
            $amount = $module['amount'] ?? 0;
            if ($proration && ($module['prorated'] ?? false)) {
                return $amount / 2; // simple half-month proration
            }
            return $amount;
        });
    }
}
