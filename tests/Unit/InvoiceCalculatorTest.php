<?php

use Modules\Billing\Services\InvoiceCalculator;

it('calculates total with proration', function () {
    $calculator = new InvoiceCalculator();
    $modules = [
        ['amount' => 100, 'prorated' => true],
        ['amount' => 50, 'prorated' => false],
    ];

    $total = $calculator->calculate($modules, true);

    expect($total)->toBe(100.0); // 50 + 50
});
