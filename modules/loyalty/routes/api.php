<?php

use Illuminate\Support\Facades\Route;
use Modules\Loyalty\Http\Controllers\Api\RedeemController;
use Modules\Loyalty\Http\Controllers\Api\BalanceController;

Route::prefix('v1/loyalty')->group(function () {
    Route::middleware('throttle:100,60')->post('redeem', RedeemController::class);
    Route::get('balance/{customerId}', BalanceController::class);
});
