<?php

namespace App\Support;

use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class TenantService
{
    public function __construct(private EventBus $bus) {}

    public function onboard(string $tenantName, string $adminName, string $adminEmail, string $password): Tenant
    {
        $tenant = Tenant::create(['name' => $tenantName]);

        $this->bus->publish('tenant.created', [$tenant]);

        $adminRole = $tenant->roles()->where('name', 'admin')->first();

        $user = $tenant->users()->create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => Hash::make($password),
        ]);

        if ($adminRole) {
            $user->roles()->attach($adminRole->id, ['tenant_id' => $tenant->id]);
        }

        return $tenant;
    }
}
