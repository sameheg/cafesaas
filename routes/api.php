<?php

use App\Http\Controllers\LeaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RealEstateReportController;
use App\Http\Controllers\RenterController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/realestate')->group(function () {
    Route::apiResource('renters', RenterController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('leases', LeaseController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('reports/summary', [RealEstateReportController::class, 'summary']);
});

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class);
});
