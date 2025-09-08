<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\TenantModuleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', [SuperAdminDashboardController::class, 'index'])
    ->name('admin.dashboard');

Route::post('/admin/tenants/{tenant}/modules/{module}', [TenantModuleController::class, 'toggle'])
    ->name('admin.modules.toggle');
