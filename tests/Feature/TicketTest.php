<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Tenant;
use App\Models\Ticket;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_ticket_listing(): void
    {
        $tenant = Tenant::factory()->create();
        app(TenantManager::class)->setTenant($tenant);

        $customer = Customer::factory()->create();
        $ticket1 = Ticket::factory()->create(['customer_id' => $customer->id]);
        $ticket2 = Ticket::factory()->create(['customer_id' => $customer->id]);
        $other = Ticket::factory()->create();

        $response = $this->get("/api/v1/support/customers/{$customer->id}/tickets");

        $response->assertOk();
        $response->assertSee($ticket1->subject);
        $response->assertSee($ticket2->subject);
        $response->assertDontSee($other->subject);
    }

    public function test_resolving_ticket_logs_feedback_to_crm(): void
    {
        $tenant = Tenant::factory()->create();
        app(TenantManager::class)->setTenant($tenant);

        $ticket = Ticket::factory()->create();

        $ticket->resolve('Great support');

        $this->assertDatabaseHas('customer_interactions', [
            'customer_id' => $ticket->customer_id,
            'type' => 'ticket_feedback',
            'details' => 'Great support',
        ]);
    }
}
