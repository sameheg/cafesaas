<?php

namespace Modules\Franchise\Providers;

use Illuminate\Support\Facades\Event;
use Modules\Franchise\Listeners\ReportsAggregateRequestedListener;
use Modules\Franchise\Models\FranchiseTemplate;
use Modules\Franchise\Observers\FranchiseTemplateObserver;
use Modules\ModuleServiceProvider;

class FranchiseServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'franchise');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');

        FranchiseTemplate::observe(FranchiseTemplateObserver::class);

        Event::listen('reports.aggregate.requested@v1', ReportsAggregateRequestedListener::class);
    }
}
