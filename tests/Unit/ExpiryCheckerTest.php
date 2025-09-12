<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Loyalty\Jobs\ExpiryCheckJob;
use Modules\Loyalty\Models\LoyaltyPoint;
use Tests\TestCase;

class ExpiryCheckerTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_points_zeroed(): void
    {
        $record = LoyaltyPoint::create([
            'tenant_id' => 't1',
            'customer_id' => 'c1',
            'balance' => 20,
            'expiry' => now()->subDay(),
        ]);

        (new ExpiryCheckJob())->handle();
        $record->refresh();
        $this->assertEquals(0, $record->balance);
    }
}
