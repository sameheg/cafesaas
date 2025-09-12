<?php

namespace Modules\Billing\Providers;

use Modules\ModuleServiceProvider;
use Illuminate\Support\Facades\Config;

class BillingServiceProvider extends ModuleServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../../config/billing.php', 'billing');
    }
}
