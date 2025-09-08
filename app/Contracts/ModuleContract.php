<?php

namespace App\Contracts;

use App\Models\Tenant;

interface ModuleContract
{
    public function install(): void;

    public function enable(Tenant $tenant): void;

    public function disable(Tenant $tenant): void;

    public function uninstall(): void;

    public function upgrade(): void;

    public function register(): void;

    public function boot(): void;
}
