<?php

namespace App\Listeners;

use App\Models\Tenant;
use App\Support\ManagesIdempotency;

class InitializeTenant
{
    use ManagesIdempotency;

    public function handle(Tenant $tenant): void
    {
        $this->once('tenant:init:'.$tenant->id, function () use ($tenant) {
            $tenant->modules()->create(['module' => 'core', 'enabled' => true]);

            $tenant->roles()->create([
                'name' => 'admin',
                'permissions' => ['*'],
            ]);

            $tenant->roles()->create([
                'name' => 'user',
                'permissions' => [],
            ]);
        });
    }
}
