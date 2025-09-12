<?php

declare(strict_types=1);

use Modules\Crm\Services\RfmCalculator;

it('calculates rfm score', function (): void {
    $calc = new RfmCalculator();
    expect($calc->calculate(10, 6, 800))->toBe(4);
    expect($calc->calculate(120, 1, 50))->toBeLessThan(3);
});
