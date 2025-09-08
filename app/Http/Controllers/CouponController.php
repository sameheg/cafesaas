<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Support\TenantManager;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $tenant = app(TenantManager::class)->tenant();

        $coupons = Coupon::where('tenant_id', $tenant->id)->get();

        return response()->json($coupons);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'integer', 'min:0'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
        ]);

        $coupon = Coupon::create($data);

        return response()->json($coupon, 201);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code' => ['sometimes', 'string', 'max:255'],
            'discount_type' => ['sometimes', 'in:percentage,fixed'],
            'value' => ['sometimes', 'integer', 'min:0'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
        ]);

        $coupon->update($data);

        return response()->json($coupon);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->noContent();
    }
}
