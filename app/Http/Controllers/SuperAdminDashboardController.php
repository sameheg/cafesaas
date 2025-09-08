<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Tenant;
use App\Models\TenantModuleState;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['modules' => function ($q) {
            $q->where('enabled', true);
        }])->get();

        $moduleUsage = TenantModuleState::select('module', DB::raw('count(*) as count'))
            ->where('enabled', true)
            ->groupBy('module')
            ->pluck('count', 'module');

        $recentLogs = AuditLog::latest()->limit(10)->get();

        return view('admin.dashboard', compact('tenants', 'moduleUsage', 'recentLogs'));
    }
}
