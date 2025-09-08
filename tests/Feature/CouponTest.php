<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Models\Tenant;
use App\Services\CheckoutService;
use App\Services\Integrations\PaymentGateway;
use App\Services\Integrations\PaymentGatewayFactory;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_coupon_applies_discount(): void
    {
        $tenant = Tenant::factory()->create();
        app(TenantManager::class)->setTenant($tenant);

        $coupon = Coupon::create([
            'code' => 'SAVE10',
            'discount_type' => 'percentage',
            'value' => 10,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'usage_limit' => 1,
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

        $this->assertEquals(900, $order->fresh()->total_cents);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'amount_cents' => 900]);
        $this->assertEquals(0, $coupon->fresh()->usage_limit);
    }
}
