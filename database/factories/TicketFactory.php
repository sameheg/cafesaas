<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'agent_id' => User::factory(),
            'category' => $this->faker->randomElement(['general', 'billing', 'technical']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => 'open',
            'subject' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
