<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Pipeline\Pipeline;

class OrderPipeline
{
    public function __construct(private Pipeline $pipeline) {}

    public function process(Order $order, Customer $customer): void
    {
        $payload = ['order' => $order, 'customer' => $customer];

        $this->pipeline
            ->send($payload)
            ->through([
                \App\Services\Order\Pipes\MarkOrderPaid::class,
                \App\Services\Order\Pipes\SendConfirmation::class,
            ])
            ->thenReturn();
    }
}
