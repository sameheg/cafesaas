<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Tenant;
use App\Notifications\CustomerCreated;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_creation_sends_notification(): void
    {
        $tenant = Tenant::factory()->create();
        app(TenantManager::class)->setTenant($tenant);
        Notification::fake();

        $customer = Customer::factory()->create(['email' => 'jane@example.com']);

        Notification::assertSentTo($customer, CustomerCreated::class);
        $this->assertDatabaseHas('customers', [
            'tenant_id' => $tenant->id,
            'email' => 'jane@example.com',
        ]);
    }

    public function test_record_interaction(): void
    {
        $tenant = Tenant::factory()->create();
        app(TenantManager::class)->setTenant($tenant);
        $customer = Customer::factory()->create();

        $interaction = $customer->recordInteraction('call', 'Discussed order');

        $this->assertDatabaseHas('customer_interactions', [
            'id' => $interaction->id,
            'customer_id' => $customer->id,
            'tenant_id' => $tenant->id,
            'type' => 'call',
        ]);
    }
}
