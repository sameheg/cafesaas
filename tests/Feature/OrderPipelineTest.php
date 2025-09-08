<?php

namespace Tests\Feature;

use App\Events\NotificationSent;
use App\Events\PaymentProcessed;
use App\Models\Customer;
use App\Models\NotificationTemplate;
use App\Models\Order;
use App\Models\Tenant;
use App\Services\OrderPipeline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderPipelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_pipeline_processes_payment_and_sends_notification(): void
    {
        Event::fake([PaymentProcessed::class, NotificationSent::class]);

        $tenant = Tenant::factory()->create();
        $customer = Customer::factory()->create(['tenant_id' => $tenant->id]);
        $order = Order::create([
            'tenant_id' => $tenant->id,
            'status' => 'pending',
            'total_cents' => 1500,
            'placed_at' => now(),
        ]);

        NotificationTemplate::create([
            'tenant_id' => $tenant->id,
            'key' => 'order.confirmation',
            'push_body' => 'Thanks for your order!',
        ]);

        app(OrderPipeline::class)->process($order, $customer);

        Event::assertDispatched(PaymentProcessed::class);
        Event::assertDispatched(NotificationSent::class);
    }
}
