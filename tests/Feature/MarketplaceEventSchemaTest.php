<?php

use Modules\Marketplace\Events\BidAwarded;
use Modules\Marketplace\Models\Bid;

it('matches bid awarded schema', function () {
    $bid = Bid::make(['id' => '1', 'store_id' => 'sup1']);
    $event = new BidAwarded($bid);
    $payload = $event->toPayload();
    expect($payload)->toHaveKeys(['bid_id','supplier']);
});
