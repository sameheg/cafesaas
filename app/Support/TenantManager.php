<?php

namespace App\Support;

use App\Models\Tenant;

class TenantManager
{
    protected ?Tenant $tenant = null;

    public function setTenant(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function tenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function resolveFromHost(string $host): ?Tenant
    {
        $parts = explode('.', $host);
        $domain = implode('.', array_slice($parts, -2));

        $this->tenant = Tenant::where('domain', $host)
            ->orWhere('domain', $domain)
            ->first();

        return $this->tenant;
    }
}
