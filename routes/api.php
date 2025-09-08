<?php

use App\Events\OrderCreated;
use App\Events\OrderShipped;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RealEstateReportController;
use App\Http\Controllers\RenterController;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
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
});

Route::prefix('v1/checkout')->group(function () {
    Route::post('address', function (Request $request, CheckoutService $checkout) {
        $data = $request->validate([
            'tenant_id' => 'required|integer',
            'address' => 'required|array',
        ]);

        $checkout->setAddress($data['tenant_id'], $data['address']);

        return response()->json(['status' => 'address_saved']);
    });

    Route::post('shipping', function (Request $request, CheckoutService $checkout) {
        $data = $request->validate([
            'tenant_id' => 'required|integer',
            'shipping' => 'required|array',
        ]);

        $checkout->setShipping($data['tenant_id'], $data['shipping']);

        return response()->json(['status' => 'shipping_saved']);
    });

    Route::post('payment', function (Request $request, CheckoutService $checkout) {
        $data = $request->validate([
            'tenant_id' => 'required|integer',
            'order_id' => 'required|integer',
            'provider' => 'required|string',
            'currency' => 'nullable|string|size:3',
            'coupon' => 'nullable|string',
        ]);

        $order = Order::where('tenant_id', $data['tenant_id'])->findOrFail($data['order_id']);

        $payment = $checkout->processPayment(
            $data['tenant_id'],
            $order,
            $data['provider'],
            $data,
            $data['coupon'] ?? null
        );

        return response()->json(['status' => $payment->status, 'payment_id' => $payment->id]);
    });
});

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('coupons', CouponController::class)->only(['index', 'store', 'update', 'destroy']);
});

Route::prefix('v1/orders')->group(function () {
    Route::post('/', function (Request $request) {
        $data = $request->validate([
            'tenant_id' => 'required|integer',
            'restaurant_table_id' => 'required|integer',
            'total_cents' => 'nullable|integer',
        ]);

        $order = Order::create($data);
        event(new OrderCreated($order));

        return response()->json(['id' => $order->id, 'status' => $order->status]);
    });

    Route::post('{order}/ship', function (Order $order) {
        event(new OrderShipped($order));

        return response()->json(['status' => $order->status]);
    });

    Route::get('{order}', function (Order $order) {
        return response()->json(['id' => $order->id, 'status' => $order->status]);
    });
});
