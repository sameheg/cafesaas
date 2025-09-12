<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Services\DunningHandler;

uses(RefreshDatabase::class);

it('suspends after three attempts', function () {
    $handler = new DunningHandler();
    $invoice = Invoice::factory()->create();

    $action = $handler->handle($invoice, 3);

    expect($action)->toBe('suspend');
});
