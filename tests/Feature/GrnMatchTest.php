<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Procurement\Models\Bid;
use Modules\Procurement\Models\Grn;
use Modules\Procurement\Models\Po;
use Modules\Procurement\Models\Rfq;
use Modules\Procurement\Services\MatchService;

uses(RefreshDatabase::class);

it('matches GRN to PO', function () {
    $rfq = Rfq::create(['tenant_id' => 'tenant', 'items' => [], 'status' => 'open']);
    $bid = Bid::create(['tenant_id' => 'tenant', 'rfq_id' => $rfq->id, 'supplier_id' => 'sup1', 'price' => 50]);
    $po = Po::create(['tenant_id' => 'tenant', 'bid_id' => $bid->id, 'supplier_id' => 'sup1', 'amount' => 50, 'status' => 'sent']);

    $grn = Grn::create(['tenant_id' => 'tenant', 'po_id' => $po->id, 'received_qty' => 50]);

    $service = new MatchService();
    if ($service->threeWayMatch($po, $grn, 50)) {
        $po->match();
    }

    expect($po->fresh()->status)->toBe('matched');
});
