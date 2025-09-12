<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Modules\Crm\Services\CohortAnalyzer;

it('splits customers into cohorts', function (): void {
    $analyzer = new CohortAnalyzer();
    $customers = Collection::times(5, fn ($i) => $i);
    $cohorts = $analyzer->split($customers);
    expect($cohorts['a']->count() + $cohorts['b']->count())->toBe(5);
});
