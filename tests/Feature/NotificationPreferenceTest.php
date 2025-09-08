<?php

namespace Tests\Feature;

use App\Events\DomainEvent;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationPreferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_preference(): void
    {
        $tenant = Tenant::factory()->create();

        $event = DomainEvent::ORDER_CREATED->value;
        $response = $this->postJson("/api/v1/tenants/{$tenant->id}/notification-preferences/{$event}/mail", [
            'enabled' => false,
        ]);

        $response->assertOk()->assertJson([
            'tenant_id' => $tenant->id,
            'template_key' => $event,
            'channel' => 'mail',
            'enabled' => false,
        ]);

        $this->assertDatabaseHas('notification_preferences', [
            'tenant_id' => $tenant->id,
            'template_key' => $event,
            'channel' => 'mail',
            'enabled' => false,
        ]);
    }
}
