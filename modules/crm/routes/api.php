<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Crm\Http\Controllers\CampaignController;
use Modules\Crm\Http\Controllers\SegmentController;

Route::prefix('v1/crm')->group(function (): void {
    Route::post('campaigns', [CampaignController::class, 'store'])->middleware('throttle:10,1');
    Route::get('segments', [SegmentController::class, 'index']);
});
