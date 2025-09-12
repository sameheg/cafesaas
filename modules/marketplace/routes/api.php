<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketplace\Http\Controllers\BidController;
use Modules\Marketplace\Http\Controllers\StoreController;

Route::prefix('v1/marketplace')->group(function () {
    Route::middleware('throttle:50,60')->post('bids', [BidController::class, 'store']);
    Route::get('stores/{supplier_id}', [StoreController::class, 'show']);
});
