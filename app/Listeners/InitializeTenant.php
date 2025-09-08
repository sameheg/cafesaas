<?php

namespace App\Listeners;

use App\Models\Tenant;

class InitializeTenant
{
    public function handle(Tenant $tenant): void
    {
        $tenant->modules()->create(['module' => 'core', 'enabled' => true]);

        $tenant->roles()->create([
            'name' => 'admin',
            'permissions' => ['*'],
        ]);

        $tenant->roles()->create([
            'name' => 'user',
            'permissions' => [],
        ]);
    }
}
