<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\PaymentSchedule;
use App\Models\Property;
use App\Support\TenantManager;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RealEstateReportController extends Controller
{
    public function summary(Request $request, TenantManager $tenantManager)
    {
        $tenantId = $tenantManager->tenant()?->id;
        $start = Carbon::parse($request->input('start', Carbon::now()->startOfMonth()));
        $end = Carbon::parse($request->input('end', Carbon::now()->endOfMonth()));

        $totalUnits = Property::where('tenant_id', $tenantId)->sum('units');
        $activeLeases = Lease::where('tenant_id', $tenantId)
            ->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())
            ->count();

        $occupancy = $totalUnits > 0 ? ($activeLeases / $totalUnits) * 100 : 0;

        $revenue = PaymentSchedule::where('tenant_id', $tenantId)
            ->whereBetween('due_date', [$start, $end])
            ->sum('amount_cents');

        return response()->json([
            'occupancy_rate' => round($occupancy, 2),
            'revenue_cents' => $revenue,
        ]);
    }
}
