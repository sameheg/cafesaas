<?php

namespace Modules\Loyalty\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Loyalty\Models\LoyaltyPoint;

class LoyaltySeeder extends Seeder
{
    public function run(): void
    {
        // Seed abuse scenario: high velocity earns
        LoyaltyPoint::create([
            'tenant_id' => 't1',
            'customer_id' => 'abusive',
            'balance' => 1000,
            'expiry' => now()->addMonth(),
        ]);

        // Seed expiry scenario
        LoyaltyPoint::create([
            'tenant_id' => 't1',
            'customer_id' => 'expired',
            'balance' => 50,
            'expiry' => now()->subDay(),
        ]);
    }
}
