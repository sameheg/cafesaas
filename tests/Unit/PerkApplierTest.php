<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Modules\Membership\Models\MembershipPerk;
use Tests\TestCase;

class PerkApplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_perks_retrieved_by_tier(): void
    {
        MembershipPerk::create([
            'id' => (string) Str::ulid(),
            'tenant_id' => 't1',
            'tier' => 'gold',
            'description' => 'Free coffee',
        ]);

        $perks = MembershipPerk::where('tier', 'gold')->pluck('description');

        $this->assertSame(['Free coffee'], $perks->toArray());
    }
}
