<?php

namespace App\Console\Commands;

use App\Models\IntegrationConfig;
use App\Models\WebhookKey;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RotateWebhookKey extends Command
{
    protected $signature = 'webhook:rotate {service} {tenant_id?}';

    protected $description = 'Rotate webhook signing keys for a service';

    public function handle(): int
    {
        $service = $this->argument('service');
        $tenantId = $this->argument('tenant_id');

        $tenants = $tenantId
            ? collect([$tenantId])
            : IntegrationConfig::where('service', $service)->pluck('tenant_id');

        foreach ($tenants as $tid) {
            WebhookKey::where('tenant_id', $tid)
                ->where('service', $service)
                ->update(['active' => false]);

            WebhookKey::create([
                'tenant_id' => $tid,
                'service' => $service,
                'key' => Str::random(40),
                'active' => true,
            ]);
        }

        $this->info('Keys rotated.');

        return self::SUCCESS;
    }
}
