<?php

namespace Modules\Marketplace\Services;

class ScoringCalculator
{
    public function score(float $price, int $leadTime): float
    {
        return max(0, 100 - $price - ($leadTime * 10));
    }
}
