<?php

declare(strict_types=1);

namespace Modules\Crm\Services;

class RfmCalculator
{
    /**
     * Calculate an RFM score from basic metrics.
     */
    public function calculate(int $recency, int $frequency, float $monetary): int
    {
        $r = $recency <= 30 ? 5 : ($recency <= 60 ? 4 : ($recency <= 90 ? 3 : ($recency <= 120 ? 2 : 1)));
        $f = $frequency >= 10 ? 5 : ($frequency >= 5 ? 4 : ($frequency >= 3 ? 3 : ($frequency >= 1 ? 2 : 1)));
        $m = $monetary >= 1000 ? 5 : ($monetary >= 500 ? 4 : ($monetary >= 250 ? 3 : ($monetary > 0 ? 2 : 1)));

        return (int) round(($r + $f + $m) / 3);
    }
}
