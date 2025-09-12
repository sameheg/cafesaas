<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\Marketplace\Events\BidAwarded;
use Modules\Marketplace\Models\Bid;

uses(RefreshDatabase::class);

it('emits event when bid awarded', function () {
    Event::fake();

    $bid = Bid::create([
        'tenant_id' => 't',
        'rfq_id' => 'r1',
        'store_id' => 's1',
        'price' => 1.0,
        'status' => Bid::STATUS_OPEN,
    ]);

    $bid->award();

    expect($bid->status)->toBe(Bid::STATUS_AWARDED);
    Event::assertDispatched(BidAwarded::class);
});
