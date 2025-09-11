<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Procurement\Models\Po;
use Modules\Procurement\Models\Rfq;
use Modules\Procurement\Models\Bid;

uses(RefreshDatabase::class);

it('transitions a PO through workflow and emits event', function () {
    $rfq = Modules\Procurement\Models\Rfq::create([
        'tenant_id' => 'tenant',
        'items' => [],
        'status' => 'open',
    ]);
    $bid = Modules\Procurement\Models\Bid::create([
        'tenant_id' => 'tenant',
        'rfq_id' => $rfq->id,
        'supplier_id' => 'sup1',
        'price' => 100,
    ]);

    $po = Po::create([
        'tenant_id' => 'tenant',
        'bid_id' => $bid->id,
        'supplier_id' => 'sup1',
        'amount' => 100,
        'status' => 'draft',
    ]);


    $po->send();
    expect($po->fresh()->status)->toBe('sent');

    $po->receive();
    expect($po->fresh()->status)->toBe('received');

    $po->match();
    expect($po->fresh()->status)->toBe('matched');
});
