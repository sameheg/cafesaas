<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\RestaurantTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_order_with_payment(): void
    {
        $table = RestaurantTable::create([
            'tenant_id' => 1,
            'name' => 'A1',
            'seats' => 4,
            'status' => 'available',
        ]);

        $order = Order::create([
            'tenant_id' => 1,
            'restaurant_table_id' => $table->id,
            'status' => 'pending',
            'total_cents' => 1000,
        ]);

        $payment = OrderPayment::create([
            'tenant_id' => 1,
            'order_id' => $order->id,
            'method' => 'cash',
            'amount_cents' => 1000,
        ]);

        $this->assertCount(1, $order->payments);
        $this->assertEquals($order->id, $payment->order->id);
    }
}
