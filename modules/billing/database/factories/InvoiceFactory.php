<?php

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\Invoice;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'tenant_id' => $this->faker->uuid(),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'status' => 'due',
            'due_date' => now()->addDays(30),
        ];
    }
}
