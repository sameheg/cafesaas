<?php

namespace Modules\Procurement\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\ModuleServiceProvider;
use Modules\Procurement\Events\PoCreated;
use Modules\Procurement\Listeners\InventoryLowStockListener;

class ProcurementServiceProvider extends ModuleServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');

        Event::listen('inventory.low.stock@v1', InventoryLowStockListener::class);
    }
}
