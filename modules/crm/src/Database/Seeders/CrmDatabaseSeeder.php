<?php

declare(strict_types=1);

namespace Modules\Crm\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Crm\Models\Campaign;
use Modules\Crm\Models\Customer;
use Modules\Crm\Models\Segment;

class CrmDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $segment = Segment::create([
            'tenant_id' => 'tenant',
            'name' => 'lapsed',
            'criteria' => ['rfm' => 1],
        ]);

        $customer = Customer::create([
            'tenant_id' => 'tenant',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'rfm_score' => 1,
            'state' => 'lapsed',
        ]);

        Campaign::create([
            'tenant_id' => 'tenant',
            'segment_id' => $segment->id,
            'status' => 'sent',
            'redemption_rate' => 20.0,
        ]);
    }
}
