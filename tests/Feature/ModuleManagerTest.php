<?php

namespace Tests\Feature;

use App\Events\DomainEvent;
use App\Models\Tenant;
use App\Support\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ModuleManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_prevents_enabling_without_dependencies(): void
    {
        $tenant = Tenant::factory()->create();
        $manager = new ModuleManager;

        $this->expectException(ValidationException::class);
        $manager->toggle($tenant, 'analytics', true);
    }

    public function test_logs_module_toggles(): void
    {
        $tenant = Tenant::factory()->create();
        $manager = new ModuleManager;

        $manager->toggle($tenant, 'billing', true);

        $this->assertDatabaseHas('tenant_module_states', [
            'tenant_id' => $tenant->id,
            'module' => 'billing',
            'enabled' => true,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'tenant_id' => $tenant->id,
            'action' => DomainEvent::MODULE_TOGGLED->value,
        ]);
    }
}
