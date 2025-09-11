<?php

namespace Modules\Procurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Procurement\Models\Bid;
use Modules\Procurement\Models\Po;
use Modules\Procurement\Models\Rfq;

class ProcurementDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $rfq = Rfq::create([
            'tenant_id' => 'tenant',
            'items' => [['item' => 'Widget', 'qty' => 10]],
            'status' => 'open',
        ]);

        $bid1 = Bid::create([
            'tenant_id' => 'tenant',
            'rfq_id' => $rfq->id,
            'supplier_id' => 'sup1',
            'price' => 100,
        ]);

        $bid2 = Bid::create([
            'tenant_id' => 'tenant',
            'rfq_id' => $rfq->id,
            'supplier_id' => 'sup2',
            'price' => 110,
        ]);

        Po::create([
            'tenant_id' => 'tenant',
            'bid_id' => $bid1->id,
            'supplier_id' => $bid1->supplier_id,
            'amount' => $bid1->price,
            'status' => 'draft',
        ]);
    }
}
