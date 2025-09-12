<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Modules\Membership\Events\TierUpgraded;
use Modules\Membership\Models\Membership;
use Tests\TestCase;

class TierUpgradeTest extends TestCase
{
    use RefreshDatabase;

    public function test_upgrade_emits_event(): void
    {
        Event::fake();

        $membership = Membership::create([
            'id' => (string) Str::ulid(),
            'tenant_id' => 't1',
            'customer_id' => 'c1',
            'tier' => 'silver',
            'expiry' => now()->addDay(),
            'status' => 'active',
        ]);

        $membership->upgrade('gold');

        Event::assertDispatched(TierUpgraded::class, function ($event) use ($membership) {
            return $event->memberId === $membership->id && $event->tier === 'gold';
        });
    }
}
