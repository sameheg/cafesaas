<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test tenant and associated user for local development
        $tenant = Tenant::factory()->create([
            'name' => 'Test Tenant',
        ]);

        User::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            ShippingProviderSeeder::class,
            NotificationChannelSeeder::class,
            NotificationPreferenceSeeder::class,
        ]);
    }
}
