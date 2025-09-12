<?php

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Models\Payment;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'tenant_id' => $this->faker->uuid(),
            'invoice_id' => Invoice::factory(),
            'method' => $this->faker->randomElement(['card', 'paypal']),
            'status' => 'success',
        ];
    }
}
