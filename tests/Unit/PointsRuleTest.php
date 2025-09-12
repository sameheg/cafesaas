<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Loyalty\Services\LoyaltyService;
use Tests\TestCase;

class PointsRuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_earn_and_burn_points(): void
    {
        $service = new LoyaltyService();
        $service->earn('t1', 'c1', 50);

        $this->assertEquals(50, $service->balance('t1', 'c1'));

        $service->burn('t1', 'c1', 20);
        $this->assertEquals(30, $service->balance('t1', 'c1'));
    }
}
