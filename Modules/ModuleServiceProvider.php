<?php

namespace Modules;

use App\Contracts\ModuleContract;
use App\Models\Tenant;
use Illuminate\Support\ServiceProvider;

abstract class ModuleServiceProvider extends ServiceProvider implements ModuleContract
{
    public function install(): void {}
    public function enable(Tenant $tenant): void {}
    public function disable(Tenant $tenant): void {}
    public function uninstall(): void {}
    public function upgrade(): void {}
    public function register(): void {}
    public function boot(): void {}
}
