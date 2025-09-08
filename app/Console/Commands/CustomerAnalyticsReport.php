<?php

namespace App\Console\Commands;

use App\Services\CustomerAnalytics;
use Illuminate\Console\Command;

class CustomerAnalyticsReport extends Command
{
    protected $signature = 'crm:analytics';

    protected $description = 'Show basic customer analytics';

    public function handle(CustomerAnalytics $analytics): int
    {
        $this->info('Total customers: '.$analytics->totalCustomers());
        $this->info('Total interactions: '.$analytics->totalInteractions());

        return self::SUCCESS;
    }
}
