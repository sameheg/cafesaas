<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationPreferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_preference(): void
    {
        $tenant = Tenant::factory()->create();

        $response = $this->postJson("/api/v1/tenants/{$tenant->id}/notification-preferences/order.created/mail", [
            'enabled' => false,
        ]);

        $response->assertOk()->assertJson([
            'tenant_id' => $tenant->id,
            'template_key' => 'order.created',
            'channel' => 'mail',
            'enabled' => false,
        ]);

        $this->assertDatabaseHas('notification_preferences', [
            'tenant_id' => $tenant->id,
            'template_key' => 'order.created',
            'channel' => 'mail',
            'enabled' => false,
        ]);
    }
}
