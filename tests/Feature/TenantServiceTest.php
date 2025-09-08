<?php

namespace Tests\Feature;

use App\Support\TenantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_onboard_enables_required_modules(): void
    {
        $service = app(TenantService::class);

        $tenant = $service->onboard('Acme', 'Admin', 'admin@example.com', 'secret');

        $this->assertDatabaseHas('tenant_module_states', [
            'tenant_id' => $tenant->id,
            'module' => 'core',
            'enabled' => true,
        ]);
        $this->assertDatabaseHas('tenant_module_states', [
            'tenant_id' => $tenant->id,
            'module' => 'security',
            'enabled' => true,
        ]);
        $this->assertDatabaseHas('tenant_module_states', [
            'tenant_id' => $tenant->id,
            'module' => 'billing',
            'enabled' => true,
        ]);
    }
}
