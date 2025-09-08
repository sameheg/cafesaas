<?php

namespace App\Services\Order\Pipes;

use App\Jobs\SendNotification;
use App\Models\Customer;
use Closure;

class SendConfirmation
{
    public function handle(array $payload, Closure $next): mixed
    {
        /** @var Customer $customer */
        $customer = $payload['customer'];

        SendNotification::dispatchSync(Customer::class, $customer->id, 'order.confirmation', ['push']);

        return $next($payload);
    }
}
