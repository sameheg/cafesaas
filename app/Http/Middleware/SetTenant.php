<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\TenantManager;
use Closure;
use Illuminate\Http\Request;

class SetTenant
{
    public function __construct(private TenantManager $manager) {}

    public function handle(Request $request, Closure $next)
    {
        $tenantId = $request->header('X-Tenant-ID');

        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
            if ($tenant) {
                $this->manager->setTenant($tenant);
            }
        }

        return $next($request);
    }
}
