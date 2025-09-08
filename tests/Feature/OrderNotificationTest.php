<?php

namespace Tests\Feature;

use App\Events\OrderDelivered;
use App\Events\OrderShipped;
use App\Models\Customer;
use App\Models\NotificationPreference;
use App\Models\Order;
use App\Models\Tenant;
use App\Notifications\Channels\SmsChannel;
use App\Notifications\OrderDeliveredNotification;
use App\Notifications\OrderShippedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_shipped_sends_notifications_based_on_preferences(): void
    {
        Notification::fake();

        $tenant = Tenant::factory()->create();
        $customer = Customer::factory()->create(['tenant_id' => $tenant->id]);

        NotificationPreference::create([
            'tenant_id' => $tenant->id,
            'template_key' => 'order.shipped',
            'channel' => 'mail',
            'enabled' => true,
        ]);
        NotificationPreference::create([
            'tenant_id' => $tenant->id,
            'template_key' => 'order.shipped',
            'channel' => 'sms',
            'enabled' => true,
        ]);

        $order = new Order(['tenant_id' => $tenant->id]);
        $order->setRelation('customer', $customer);

        event(new OrderShipped($order));

        Notification::assertSentTo(
            $customer,
            OrderShippedNotification::class,
            function (OrderShippedNotification $notification) use ($customer) {
                $channels = $notification->via($customer);

                return in_array('mail', $channels) && in_array(SmsChannel::class, $channels);
            }
        );
    }

    public function test_order_delivered_sends_notifications_based_on_preferences(): void
    {
        Notification::fake();

        $tenant = Tenant::factory()->create();
        $customer = Customer::factory()->create(['tenant_id' => $tenant->id]);

        NotificationPreference::create([
            'tenant_id' => $tenant->id,
            'template_key' => 'order.delivered',
            'channel' => 'mail',
            'enabled' => true,
        ]);
        NotificationPreference::create([
            'tenant_id' => $tenant->id,
            'template_key' => 'order.delivered',
            'channel' => 'sms',
            'enabled' => true,
        ]);

        $order = new Order(['tenant_id' => $tenant->id]);
        $order->setRelation('customer', $customer);

        event(new OrderDelivered($order));

        Notification::assertSentTo(
            $customer,
            OrderDeliveredNotification::class,
            function (OrderDeliveredNotification $notification) use ($customer) {
                $channels = $notification->via($customer);

                return in_array('mail', $channels) && in_array(SmsChannel::class, $channels);
            }
        );
    }
}
