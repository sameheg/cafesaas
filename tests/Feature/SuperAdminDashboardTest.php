<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Support\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_tenants_and_modules(): void
    {
        $tenant = Tenant::factory()->create(['name' => 'Acme']);
        $manager = new ModuleManager();
        $manager->toggle($tenant, 'billing', true);

        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Acme');
        $response->assertSee('billing');
    }
}
