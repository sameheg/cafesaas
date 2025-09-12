<?php

namespace Modules\Franchise\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Franchise\Models\FranchiseBranch;
use Modules\Franchise\Models\FranchiseTemplate;

class FranchiseSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = (string) str()->uuid();
        $branch = FranchiseBranch::create([
            'tenant_id' => $tenant,
            'name' => 'Main Branch',
            'overrides' => [],
        ]);

        FranchiseTemplate::create([
            'tenant_id' => $tenant,
            'type' => 'recipe',
            'data' => ['price' => 10],
            'version' => 1,
            'status' => 'Local',
        ]);
    }
}
