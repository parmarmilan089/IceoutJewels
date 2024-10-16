<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Apply middleware to all API routes
Route::middleware('api')->group(function () {

    Route::get('/test', function () {
        return response()->json(['message' => 'API is working']);
    });
    // Admin routes
    Route::group(['prefix' => 'admin'], function () {
        Route::post('login', [App\Http\Controllers\AdminAuthController::class, 'login']);
        Route::post('register', [App\Http\Controllers\AdminAuthController::class, 'register']);

        //admin route
        Route::middleware('auth:admin')->group(function () {
            Route::get('fetch', [App\Http\Controllers\AdminAuthController::class, 'fetch']);
        });
    });


    // Customer routes
    Route::group(['prefix' => 'customer'], function () {
        Route::post('login', [App\Http\Controllers\CustomerAuthController::class, 'login']);
        Route::post('register', [App\Http\Controllers\CustomerAuthController::class, 'register']);
        //admin route
        Route::middleware('auth:customer')->group(function () {
            Route::get('fetch', [App\Http\Controllers\CustomerAuthController::class, 'fetch']);
        });
    });

});
