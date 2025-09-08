<?php

namespace Database\Seeders;

use App\Models\IntegrationConfig;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class ShippingProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = ['aramex', 'fedex', 'dhl'];

        Tenant::all()->each(function ($tenant) use ($providers) {
            foreach ($providers as $provider) {
                IntegrationConfig::firstOrCreate(
                    ['tenant_id' => $tenant->id, 'service' => $provider],
                    ['config_json' => ['api_key' => '', 'api_secret' => '']]
                );
            }
        });
    }
}
