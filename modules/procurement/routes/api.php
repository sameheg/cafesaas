<?php

use Illuminate\Support\Facades\Route;
use Modules\Procurement\Http\Controllers\PoController;
use Modules\Procurement\Http\Controllers\RfqController;

Route::middleware('api')->prefix('v1/procurement')->group(function () {
    Route::post('rfqs', [RfqController::class, 'store'])->middleware('throttle:20,1');
    Route::post('pos/{rfq_id}', [PoController::class, 'store']);
});
