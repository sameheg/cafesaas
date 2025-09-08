<?php

namespace App\Support;

use App\Events\DomainEvent;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class TenantService
{
    public function __construct(private EventBus $bus, private ModuleRegistry $registry) {}

    public function onboard(string $tenantName, string $adminName, string $adminEmail, string $password): Tenant
    {
        $tenant = Tenant::create(['name' => $tenantName]);

        $this->bus->publish(DomainEvent::TENANT_CREATED->value, [$tenant]);

        $adminRole = $tenant->roles()->where('name', 'admin')->first();

        $user = $tenant->users()->create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => Hash::make($password),
        ]);

        if ($adminRole) {
            $user->roles()->attach($adminRole->id, ['tenant_id' => $tenant->id]);
        }

        foreach ($this->registry->all() as $key => $meta) {
            if (($meta['required'] ?? false) === true) {
                $this->registry->enable($tenant, $key);
            }
        }

        return $tenant;
    }
}
