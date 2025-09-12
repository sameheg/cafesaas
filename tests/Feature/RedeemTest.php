<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Loyalty\Services\LoyaltyService;
use Tests\TestCase;

class RedeemTest extends TestCase
{
    use RefreshDatabase;

    public function test_redeem_endpoint_checks_balance(): void
    {
        $service = new LoyaltyService();
        $service->earn('t1', 'c1', 10);

        $response = $this->postJson('/v1/loyalty/redeem', [
            'tenant_id' => 't1',
            'customer_id' => 'c1',
            'points' => 15,
        ]);

        $response->assertStatus(402);
    }

    public function test_redeem_success(): void
    {
        $service = new LoyaltyService();
        $service->earn('t1', 'c1', 10);

        $response = $this->postJson('/v1/loyalty/redeem', [
            'tenant_id' => 't1',
            'customer_id' => 'c1',
            'points' => 5,
        ]);

        $response->assertOk()->assertJson(['success' => true]);
    }
}
