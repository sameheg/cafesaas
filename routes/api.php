<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RenterController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\RealEstateReportController;

Route::prefix('v1/realestate')->group(function () {
    Route::apiResource('renters', RenterController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('leases', LeaseController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('reports/summary', [RealEstateReportController::class, 'summary']);
});
