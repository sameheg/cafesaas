<?php

namespace Tests\Feature;

use App\Events\ModuleRegistered;
use App\Events\ModuleToggled;
use App\Models\Tenant;
use App\Support\ModuleManager;
use App\Support\ModuleRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ModuleRegistryTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_dispatches_event(): void
    {
        Event::fake();

        $registry = new ModuleRegistry;
        $registry->register('crm', ['name' => 'CRM']);

        Event::assertDispatched(ModuleRegistered::class, function ($event) {
            return $event->module === 'crm';
        });
    }

    public function test_hooks_fire_on_toggle(): void
    {
        Event::fake([ModuleToggled::class]);

        $registry = app(ModuleRegistry::class);
        $manager = new ModuleManager($registry);
        $tenant = Tenant::factory()->create();

        $fired = false;
        $registry->hook('module.enabled', function (Tenant $t, string $module) use ($tenant, &$fired) {
            if ($t->is($tenant) && $module === 'billing') {
                $fired = true;
            }
        });

        $manager->toggle($tenant, 'billing', true);

        Event::assertDispatched(ModuleToggled::class);
        $this->assertTrue($fired);
    }
}
