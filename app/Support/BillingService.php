<?php

namespace App\Support;

use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;

class BillingService
{
    public function __construct(private CurrencyConverter $converter) {}

    /**
     * Calculate prorated amount when switching plan.
     */
    public function prorate(Subscription $subscription, Plan $newPlan, Carbon $changeDate): int
    {
        $currentPlan = $subscription->plan;
        $cycleEnds = $subscription->next_billing_at ?? $changeDate;
        $totalDays = $subscription->starts_at?->diffInDays($cycleEnds) ?: 1;
        $remainingDays = $changeDate->diffInDays($cycleEnds, false);

        $currentAmount = $subscription->amount_cents;
        $newAmount = $this->converter->convert($newPlan->price_cents, $newPlan->currency, $subscription->currency);

        $unusedValue = (int) round($currentAmount * max($remainingDays, 0) / $totalDays);

        return $newAmount - $unusedValue;
    }
}
