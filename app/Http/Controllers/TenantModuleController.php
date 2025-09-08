<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Support\ModuleManager;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;

class TenantModuleController extends Controller
{
    public function edit(Tenant $tenant, ModuleRegistry $registry, ModuleManager $manager)
    {
        $modules = $registry->all();
        $tenantModules = $tenant->modules()->pluck('enabled', 'module');
        $dependencies = $manager->dependencies();

        return view('admin.tenant-modules', compact('tenant', 'modules', 'tenantModules', 'dependencies'));
    }

    public function update(Request $request, Tenant $tenant, ModuleManager $manager, ModuleRegistry $registry)
    {
        $selected = $request->input('modules', []);
        $resolved = $manager->resolveDependencies($selected);

        $all = array_keys($registry->all());
        foreach ($all as $module) {
            $manager->toggle($tenant, $module, in_array($module, $resolved, true));
        }

        return redirect()->route('admin.tenants.modules.edit', $tenant);
    }

    public function toggle(Request $request, Tenant $tenant, string $module, ModuleManager $manager)
    {
        $enabled = $request->boolean('enabled');
        $manager->toggle($tenant, $module, $enabled);

        return redirect()->route('admin.dashboard');
    }
}
