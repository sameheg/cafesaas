<?php

use Modules\Franchise\Services\MarginGuard;
use Modules\Franchise\Services\MarginViolationException;

it('throws when price below zero', function () {
    config()->set('franchise.margin_guards', true);
    $guard = new MarginGuard();
    expect(fn () => $guard->validate(['price' => -1]))->toThrow(MarginViolationException::class);
});
