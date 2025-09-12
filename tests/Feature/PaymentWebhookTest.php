<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Modules\Billing\Events\InvoiceIssued;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('emits event when issuing invoice', function () {
    Event::fake();

    $response = postJson('/v1/billing/invoices', [
        'tenant_id' => (string) Str::uuid(),
        'modules' => [ ['amount' => 20] ],
    ]);

    $response->assertStatus(200);

    Event::assertDispatched(InvoiceIssued::class);
});
