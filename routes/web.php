<?php

use App\Http\Controllers\IntegrationManagerController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\TenantModuleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', [SuperAdminDashboardController::class, 'index'])
    ->name('admin.dashboard');

Route::post('/admin/tenants/{tenant}/modules/{module}', [TenantModuleController::class, 'toggle'])
    ->name('admin.modules.toggle');

Route::get('/admin/integrations', [IntegrationManagerController::class, 'index'])
    ->name('admin.integrations.index');
Route::post('/admin/integrations', [IntegrationManagerController::class, 'store'])
    ->name('admin.integrations.store');
