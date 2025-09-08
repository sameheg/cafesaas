<?php

namespace Database\Seeders;

use App\Models\IntegrationConfig;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class NotificationChannelSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            'twilio' => ['provider' => 'twilio', 'api_key' => ''],
            'slack' => ['webhook_url' => ''],
        ];

        Tenant::all()->each(function ($tenant) use ($services) {
            foreach ($services as $service => $config) {
                IntegrationConfig::firstOrCreate(
                    ['tenant_id' => $tenant->id, 'service' => $service],
                    ['config_json' => $config]
                );
            }
        });
    }
}
