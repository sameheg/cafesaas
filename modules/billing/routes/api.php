<?php

use Illuminate\Support\Facades\Route;
use Modules\Billing\Http\Controllers\InvoiceController;

Route::prefix('v1/billing')->group(function () {
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::get('/history/{tenant_id}', [InvoiceController::class, 'history']);
});
