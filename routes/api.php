<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\User\ChatController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\Api\LandingPageController;

Route::get('/landing-page', [LandingPageController::class, 'index']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::any('/refresh', [AuthController::class, 'refresh']);
    Route::get('/profile', [AuthController::class, 'userProfile']);
    Route::post('/update/profile', [AuthController::class, 'updateProfile']);
    Route::post('/delete/profile', [AuthController::class, 'deleteProfile']);
    Route::post('/change/password', [AuthController::class, 'changePassword']);
});