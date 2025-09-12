<?php

namespace Modules\Marketplace\Providers;

use Illuminate\Support\Facades\Event;
use Modules\Marketplace\Events\BidAwarded;
use Modules\Marketplace\Listeners\ProcurementRfqCreatedListener;
use Modules\ModuleServiceProvider;

class MarketplaceServiceProvider extends ModuleServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');

        Event::listen('procurement.rfq.created@v1', ProcurementRfqCreatedListener::class);
    }
}
