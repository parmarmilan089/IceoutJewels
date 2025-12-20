<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AddonsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/run-migrations', function () {
    try {
        Artisan::call('migrate');
        return response()->json(['message' => 'Migration Done!', 'output' => Artisan::output()]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::controller(LoginController::class)->group(function () {
    // Admin Auth Route
    Route::get('admin/login', 'adminLogin')->name('admin.login');
    Route::post('admin/login/check', 'adminLoginCheck')->name('admin.login.check');

    Route::get('check-unique-email', 'checkUniqueEmail')->name('user.checkUniqueEmail');
});


Route::prefix('admin')->name('admin.')->group(function(){
    Route::group(['middleware' => 'auth:Admin'], function () {
        Route::resource('users', UserController::class);
        Route::resource('products', ProductController::class);
        Route::post('products/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
        
        Route::resource('categories', CategoryController::class);
        Route::post('categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');
        
        // Product Variants
        Route::resource('product-variants', App\Http\Controllers\Admin\ProductVariantController::class);
        
        // Variant Options
        Route::resource('variant-options', App\Http\Controllers\Admin\VariantOptionController::class);
        
        // Product Combinations
        Route::get('products/{id}/combinations', [App\Http\Controllers\Admin\ProductCombinationController::class, 'index'])->name('products.combinations');
        Route::post('products/{id}/combinations/generate', [App\Http\Controllers\Admin\ProductCombinationController::class, 'generate'])->name('products.combinations.generate');
        Route::post('combinations/{id}/update-stock', [App\Http\Controllers\Admin\ProductCombinationController::class, 'updateStock'])->name('combinations.updateStock');
        Route::post('combinations/{id}/toggle-availability', [App\Http\Controllers\Admin\ProductCombinationController::class, 'toggleAvailability'])->name('combinations.toggleAvailability');
        
        Route::resource('addons', AddonsController::class);
        Route::resource('orders', OrderController::class);
        Route::post('settings/update', [SettingController::class, 'updateSettings'])->name('settings.updateAll');
        Route::resource('settings', SettingController::class);

        Route::post('logout', [LoginController::class, 'adminLogout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
        Route::post('profile', [ProfileController::class, 'profile_update'])->name('profile.update');
        Route::get('change_password', [ProfileController::class, 'change_password'])->name('change_password');
        Route::post('change_password', [ProfileController::class, 'update_password'])->name('change_password.update');
    });
});
