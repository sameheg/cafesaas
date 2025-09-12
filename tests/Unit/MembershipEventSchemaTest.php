<?php

namespace Tests\Unit;

use Modules\Membership\Events\TierUpgraded;
use PHPUnit\Framework\TestCase;

class MembershipEventSchemaTest extends TestCase
{
    public function test_payload_schema(): void
    {
        $event = new TierUpgraded('t1', 'm1', 'gold');

        $this->assertSame([
            'member_id' => 'm1',
            'tier' => 'gold',
        ], $event->payload());
    }
}
