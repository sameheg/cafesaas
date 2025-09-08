<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BillingDashboardController;
use App\Http\Controllers\FeatureFlagController;
use App\Http\Controllers\IntegrationManagerController;
use App\Http\Controllers\NotificationPreferenceController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\SupplyChainController;
use App\Http\Controllers\TenantModuleController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TicketController;
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
Route::get('/admin/tenants/{tenant}/notifications', [NotificationPreferenceController::class, 'dashboard'])->name('admin.notifications.preferences');

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
    Route::get('tenants/{tenant}/notification-preferences', [NotificationPreferenceController::class, 'index']);
    Route::post('tenants/{tenant}/notification-preferences/{templateKey}/{channel}', [NotificationPreferenceController::class, 'update']);
    Route::prefix('supply-chain')->group(function () {
        Route::get('inventory', [SupplyChainController::class, 'inventory']);
        Route::get('suppliers', [SupplyChainController::class, 'suppliers']);
        Route::post('link-order', [SupplyChainController::class, 'linkOrder']);
    });

    Route::prefix('support')->group(function () {
        Route::get('customers/{customer}/tickets', [TicketController::class, 'customer']);
        Route::get('agents/{user}/tickets', [TicketController::class, 'agent']);
    });
});
