<?php

namespace Modules\Franchise\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Franchise\Models\FranchiseBranch;
use Modules\Franchise\Models\FranchiseTemplate;

class ReportsAggregateRequestedListener
{
    public function handle(array $payload): void
    {
        $reportId = $payload['report_id'] ?? null;
        // Simple aggregation example
        $data = [
            'branches' => FranchiseBranch::count(),
            'templates' => FranchiseTemplate::count(),
        ];
        Log::info('franchise aggregate', ['report_id' => $reportId, 'data' => $data]);
    }
}
