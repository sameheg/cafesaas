<?php

declare(strict_types=1);

namespace Modules\Crm\Providers;

use App\Support\EventBus;
use Modules\Crm\Listeners\UpdateRfmOnOrderPaid;
use Modules\ModuleServiceProvider;

class CrmServiceProvider extends ModuleServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        app(EventBus::class)->subscribe('pos.order.paid@v1', UpdateRfmOnOrderPaid::class);
    }
}
