<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Support\ModuleManager;
use Illuminate\Http\Request;

class TenantModuleController extends Controller
{
    public function toggle(Request $request, Tenant $tenant, string $module, ModuleManager $manager)
    {
        $enabled = $request->boolean('enabled');
        $manager->toggle($tenant, $module, $enabled);

        return redirect()->route('admin.dashboard');
    }
}
