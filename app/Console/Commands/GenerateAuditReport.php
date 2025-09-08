<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class GenerateAuditReport extends Command
{
    protected $signature = 'audit:report {--from=} {--to=}';

    protected $description = 'Generate a simple audit log report';

    public function handle(): int
    {
        $query = AuditLog::query();
        if ($from = $this->option('from')) {
            $query->where('created_at', '>=', $from);
        }
        if ($to = $this->option('to')) {
            $query->where('created_at', '<=', $to);
        }

        $logs = $query->orderBy('created_at')->get(['id', 'action', 'user_id', 'tenant_id', 'created_at']);
        $this->table(['ID', 'Action', 'User', 'Tenant', 'At'], $logs->toArray());

        return self::SUCCESS;
    }
}
