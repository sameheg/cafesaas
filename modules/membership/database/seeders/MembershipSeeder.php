<?php

namespace Modules\Membership\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Membership\Models\Membership;
use Modules\Membership\Models\MembershipPerk;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        MembershipPerk::create([
            'tenant_id' => 't1',
            'tier' => 'silver',
            'description' => '5% discount on bookings',
        ]);

        MembershipPerk::create([
            'tenant_id' => 't1',
            'tier' => 'gold',
            'description' => '10% discount and free upgrades',
        ]);

        Membership::create([
            'tenant_id' => 't1',
            'customer_id' => 'c1',
            'tier' => 'silver',
            'expiry' => now()->addMonth(),
            'status' => 'active',
        ]);
    }
}
