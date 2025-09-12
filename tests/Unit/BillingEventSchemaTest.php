<?php

use Modules\Billing\Events\InvoiceIssued;
use Modules\Billing\Models\Invoice;

it('matches invoice issued schema', function () {
    $invoice = Invoice::factory()->make(['id' => '01H', 'amount' => 100]);
    $event = new InvoiceIssued($invoice);

    expect($event->toArray())->toHaveKeys(['invoice_id', 'amount']);
});
