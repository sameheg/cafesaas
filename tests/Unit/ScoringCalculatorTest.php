<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Marketplace\Services\ScoringCalculator;

uses(RefreshDatabase::class);

it('calculates score', function () {
    $calc = new ScoringCalculator();
    $score = $calc->score(20, 2);
    expect($score)->toBe(60.0);
});
