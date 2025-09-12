<?php

declare(strict_types=1);

namespace Modules\Crm\Services;

use Illuminate\Support\Collection;

class CohortAnalyzer
{
    /**
     * Split customers into A/B cohorts for testing.
     */
    public function split(Collection $customers): array
    {
        $shuffled = $customers->shuffle();
        $half = (int) ceil($shuffled->count() / 2);

        return [
            'a' => $shuffled->slice(0, $half)->values(),
            'b' => $shuffled->slice($half)->values(),
        ];
    }
}
