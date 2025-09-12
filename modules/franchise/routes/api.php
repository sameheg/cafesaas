<?php

use Illuminate\Support\Facades\Route;
use Modules\Franchise\Http\Controllers\TemplateController;
use Modules\Franchise\Http\Controllers\ReportController;

Route::prefix('v1/franchise')->middleware('api')->group(function () {
    Route::patch('/templates', [TemplateController::class, 'update'])->middleware('throttle:10,1');
    Route::get('/reports/aggregate', [ReportController::class, 'aggregate']);
});
