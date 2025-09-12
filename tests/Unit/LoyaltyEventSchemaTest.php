<?php

namespace Tests\Unit;

use Modules\Loyalty\Events\PointsEarned;
use PHPUnit\Framework\TestCase;

class LoyaltyEventSchemaTest extends TestCase
{
    public function test_payload_schema(): void
    {
        $event = new PointsEarned('t1', 'c1', 5);
        $this->assertSame([
            'customer_id' => 'c1',
            'points' => 5,
        ], $event->payload());
    }
}
