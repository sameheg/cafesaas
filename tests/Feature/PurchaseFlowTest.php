<?php

namespace Tests\Feature;

use App\Events\PaymentProcessed;
use App\Models\Coupon;
use App\Models\IntegrationConfig;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Models\Tenant;
use App\Services\CheckoutService;
use App\Services\Integrations\PaymentGateway;
use App\Services\Integrations\PaymentGatewayFactory;
use App\Services\Integrations\ShippingGatewayFactory;
use App\Services\ShippingService;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PurchaseFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_flow_processes_payment_and_dispatches_shipment(): void
    {
        Event::fake();

        $tenant = Tenant::factory()->create();
        app(TenantManager::class)->setTenant($tenant);

        $coupon = Coupon::create([
            'code' => 'SAVE10',
            'discount_type' => 'percentage',
            'value' => 10,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
        ]);

        $table = RestaurantTable::create([
            'tenant_id' => $tenant->id,
            'name' => 'T1',
            'seats' => 4,
        ]);

        $order = Order::create([
            'tenant_id' => $tenant->id,
            'restaurant_table_id' => $table->id,
            'status' => 'pending',
            'total_cents' => 1000,
        ]);

        IntegrationConfig::create([
            'tenant_id' => $tenant->id,
            'service' => 'shippo',
            'config_json' => ['base_url' => 'https://ship.test', 'api_key' => 'key'],
        ]);

        Http::fake([
            'https://ship.test/shipments' => Http::response(['tracking_number' => 'TRACK123']),
            'https://ship.test/track/TRACK123' => Http::response(['status' => 'delivered']),
        ]);

        $factory = new class extends PaymentGatewayFactory
        {
            public function make(string $provider, int $tenantId): PaymentGateway
            {
                return new class implements PaymentGateway
                {
                    public function charge(Order $order, array $details): array
                    {
                        return ['id' => '1', 'status' => 'succeeded'];
                    }
                };
            }
        };

        $checkout = new CheckoutService($factory);
        $payment = $checkout->processPayment($tenant->id, $order, 'stripe', ['currency' => 'USD'], 'SAVE10', 'pay-1');

        Event::assertDispatched(PaymentProcessed::class);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'amount_cents' => 900]);

        $shipping = new ShippingService(new ShippingGatewayFactory);
        $shipment = $shipping->dispatch($order, 'shippo');
        $this->assertEquals('TRACK123', $shipment->tracking_number);

        $shipping->refresh($shipment);
        $this->assertEquals('delivered', $shipment->fresh()->status);
    }
}
