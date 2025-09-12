<?php

namespace Modules\Marketplace\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Marketplace\Models\Bid;
use Modules\Marketplace\Models\Listing;
use Modules\Marketplace\Models\Store;

class MarketplaceDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::create([
            'tenant_id' => 'seed-tenant',
            'supplier_id' => 'sup1',
            'name' => 'Seed Store',
            'tier' => 'basic',
        ]);

        Listing::create([
            'tenant_id' => 'seed-tenant',
            'store_id' => $store->id,
            'item_id' => 'item1',
            'price' => 10,
            'stock' => 100,
        ]);

        Bid::create([
            'tenant_id' => 'seed-tenant',
            'rfq_id' => 'rfq1',
            'store_id' => $store->id,
            'price' => 9.5,
            'status' => Bid::STATUS_OPEN,
        ]);
    }
}
