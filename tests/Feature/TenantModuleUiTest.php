<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Support\ModuleRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantModuleUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_enables_dependencies(): void
    {
        $tenant = Tenant::factory()->create();
        $registry = app(ModuleRegistry::class);
        $registry->register('billing');
        $registry->register('analytics');

        $response = $this->post("/admin/tenants/{$tenant->id}/modules", [
            'modules' => ['analytics'],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tenant_module_states', [
            'tenant_id' => $tenant->id,
            'module' => 'analytics',
            'enabled' => true,
        ]);

        $this->assertDatabaseHas('tenant_module_states', [
            'tenant_id' => $tenant->id,
            'module' => 'billing',
            'enabled' => true,
        ]);
    }
}
