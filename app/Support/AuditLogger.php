<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public function __construct(private TenantManager $tenants) {}

    public function log(string $action, array $meta = [], ?int $tenantId = null, ?int $userId = null): void
    {
        AuditLog::create([
            'tenant_id' => $tenantId ?? $this->tenants->tenant()?->id,
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'meta' => $meta,
        ]);
    }
}
