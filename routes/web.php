<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BillingDashboardController;
use App\Http\Controllers\FeatureFlagController;
use App\Http\Controllers\IntegrationManagerController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\TenantModuleController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', [SuperAdminDashboardController::class, 'index'])
    ->name('admin.dashboard');

Route::get('/billing', [BillingDashboardController::class, 'index'])
    ->name('billing.dashboard');

Route::post('/admin/tenants/{tenant}/modules/{module}', [TenantModuleController::class, 'toggle'])
    ->name('admin.modules.toggle');

Route::get('/admin/integrations', [IntegrationManagerController::class, 'index'])
    ->name('admin.integrations.index');
Route::post('/admin/integrations', [IntegrationManagerController::class, 'store'])
    ->name('admin.integrations.store');

Route::middleware('auth')->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/users', [AnalyticsController::class, 'users'])->name('analytics.users');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
});

Route::prefix('api/v1')->group(function () {
    Route::get('tenants/{tenant}/feature-flags', [FeatureFlagController::class, 'index']);
    Route::post('tenants/{tenant}/feature-flags/{key}', [FeatureFlagController::class, 'update']);
    Route::get('tenants/{tenant}/theme.css', [ThemeController::class, 'css']);
    Route::post('tenants/{tenant}/theme', [ThemeController::class, 'update']);
});
