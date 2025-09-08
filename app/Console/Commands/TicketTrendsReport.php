<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class TicketTrendsReport extends Command
{
    protected $signature = 'support:ticket-trends';

    protected $description = 'Show ticket trends by category';

    public function handle(): int
    {
        $trends = Ticket::query()
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        foreach ($trends as $category => $total) {
            $this->info($category.': '.$total);
        }

        return self::SUCCESS;
    }
}
