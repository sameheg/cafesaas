<?php

use Illuminate\Support\Facades\Route;
use Modules\Membership\Http\Controllers\MembershipController;

Route::middleware('api')->group(function () {
    Route::patch('/v1/membership/{id}', [MembershipController::class, 'update']);
    Route::get('/v1/membership/perks/{tier}', [MembershipController::class, 'perks']);
});
