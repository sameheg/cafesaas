<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Marketplace\Models\Bid;

uses(RefreshDatabase::class);

it('submits bid via api', function () {
    $response = $this->postJson('/v1/marketplace/bids', [
        'rfq_id' => 'r1',
        'price' => 5,
    ]);

    $response->assertStatus(200)->assertJsonStructure(['bid_id']);
    expect(Bid::count())->toBe(1);
});
