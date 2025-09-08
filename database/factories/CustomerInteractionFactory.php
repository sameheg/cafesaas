<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CustomerInteraction>
 */
class CustomerInteractionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'type' => fake()->randomElement(['email', 'call', 'visit']),
            'details' => fake()->sentence(),
            'interaction_at' => now(),
        ];
    }
}
