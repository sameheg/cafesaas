<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\PaymentSchedule;
use App\Support\TenantManager;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaseController extends Controller
{
    public function index(TenantManager $tenantManager)
    {
        $tenantId = $tenantManager->tenant()?->id;

        return Lease::where('tenant_id', $tenantId)->get();
    }

    public function store(Request $request, TenantManager $tenantManager)
    {
        $data = $request->validate([
            'property_id' => ['required', 'integer', 'exists:property,id'],
            'renter_id' => ['required', 'integer', 'exists:renter,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'rent_cents' => ['required', 'integer', 'min:0'],
        ]);

        $lease = Lease::create([
            'tenant_id' => $tenantManager->tenant()?->id,
            ...$data,
        ]);

        $this->generateSchedule($lease);

        return response()->json($lease, 201);
    }

    public function update(Request $request, Lease $lease, TenantManager $tenantManager)
    {
        abort_if($lease->tenant_id !== $tenantManager->tenant()?->id, 404);

        $data = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'rent_cents' => ['required', 'integer', 'min:0'],
        ]);

        $lease->update($data);

        return response()->json($lease);
    }

    public function destroy(Lease $lease, TenantManager $tenantManager)
    {
        abort_if($lease->tenant_id !== $tenantManager->tenant()?->id, 404);

        $lease->delete();

        return response()->noContent();
    }

    protected function generateSchedule(Lease $lease): void
    {
        $start = Carbon::parse($lease->start_date)->startOfMonth();
        $end = Carbon::parse($lease->end_date)->startOfMonth();

        while ($start <= $end) {
            PaymentSchedule::create([
                'tenant_id' => $lease->tenant_id,
                'lease_id' => $lease->id,
                'due_date' => $start->copy(),
                'amount_cents' => $lease->rent_cents,
            ]);

            $start->addMonth();
        }
    }
}
