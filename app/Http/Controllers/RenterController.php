<?php

namespace App\Http\Controllers;

use App\Models\Renter;
use App\Support\TenantManager;
use Illuminate\Http\Request;

class RenterController extends Controller
{
    public function index(TenantManager $tenantManager)
    {
        $tenantId = $tenantManager->tenant()?->id;

        return Renter::where('tenant_id', $tenantId)->get();
    }

    public function store(Request $request, TenantManager $tenantManager)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
        ]);

        $renter = Renter::create([
            'tenant_id' => $tenantManager->tenant()?->id,
            ...$data,
        ]);

        return response()->json($renter, 201);
    }

    public function update(Request $request, Renter $renter, TenantManager $tenantManager)
    {
        abort_if($renter->tenant_id !== $tenantManager->tenant()?->id, 404);

        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
        ]);

        $renter->update($data);

        return response()->json($renter);
    }

    public function destroy(Renter $renter, TenantManager $tenantManager)
    {
        abort_if($renter->tenant_id !== $tenantManager->tenant()?->id, 404);

        $renter->delete();

        return response()->noContent();
    }
}
