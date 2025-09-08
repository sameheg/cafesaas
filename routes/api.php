<?php

use App\Http\Controllers\LeaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RealEstateReportController;
use App\Http\Controllers\RenterController;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/realestate')->group(function () {
    Route::apiResource('renters', RenterController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('leases', LeaseController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('reports/summary', [RealEstateReportController::class, 'summary']);
});

Route::prefix('v1/cart')->group(function () {
    Route::post('items', function (Request $request, CartService $cart) {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'tenant_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->addItem($data['user_id'], $data['tenant_id'], $data['product_id'], $data['quantity']);

        return response()->json(['status' => 'added']);
    });

    Route::delete('items', function (Request $request, CartService $cart) {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'tenant_id' => 'required|integer',
            'product_id' => 'required|integer',
        ]);

        $cart->removeItem($data['user_id'], $data['tenant_id'], $data['product_id']);

        return response()->json(['status' => 'removed']);
    });

    Route::get('total', function (Request $request, CartService $cart) {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'tenant_id' => 'required|integer',
            'branch_id' => 'nullable|integer',
        ]);

        $total = $cart->total($data['user_id'], $data['tenant_id'], $data['branch_id'] ?? null);

        return response()->json(['total' => $total]);
    });
Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class);
});
