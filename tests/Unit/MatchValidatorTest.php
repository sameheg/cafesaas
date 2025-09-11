<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Procurement\Models\Bid;
use Modules\Procurement\Models\Grn;
use Modules\Procurement\Models\Po;
use Modules\Procurement\Models\Rfq;
use Modules\Procurement\Services\MatchService;

uses(RefreshDatabase::class);

it('validates three way match', function () {
    $rfq = Rfq::create(['tenant_id' => 'tenant', 'items' => [], 'status' => 'open']);
    $bid = Bid::create(['tenant_id' => 'tenant', 'rfq_id' => $rfq->id, 'supplier_id' => 'sup1', 'price' => 100]);
    $po = Po::create(['tenant_id' => 'tenant', 'bid_id' => $bid->id, 'supplier_id' => 'sup1', 'amount' => 100, 'status' => 'draft']);
    $grn = Grn::create(['tenant_id' => 'tenant', 'po_id' => $po->id, 'received_qty' => 100]);

    $service = new MatchService();
    expect($service->threeWayMatch($po, $grn, 100))->toBeTrue();
    expect($service->threeWayMatch($po, $grn, 90))->toBeFalse();
});
