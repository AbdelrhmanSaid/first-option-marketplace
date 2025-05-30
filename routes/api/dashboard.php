<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your dashboard.
|
*/

Route::get('/', [\App\Http\Controllers\Api\Dashboard\HomeController::class, 'index'])->name('index');

Route::middleware('auth:admins-api')->group(function () {
    Route::get('profile', [\App\Http\Controllers\Api\Dashboard\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\Api\Dashboard\ProfileController::class, 'update'])->name('profile.update');

    Route::get('roles', [\App\Http\Controllers\Api\Dashboard\RoleController::class, 'index'])->name('roles.index');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register auth routes for your dashboard.
|
*/

Route::prefix('auth')->group(function () {
    Route::middleware('guest:admins-api')->group(function () {
        Route::post('login', [\App\Http\Controllers\Api\Dashboard\Auth\AuthenticatedSessionController::class, 'store'])->name('login');
        Route::post('forgot-password', [\App\Http\Controllers\Api\Dashboard\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::post('reset-password', [\App\Http\Controllers\Api\Dashboard\Auth\NewPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('auth:admins-api')->group(function () {
        Route::post('register', [\App\Http\Controllers\Api\Dashboard\Auth\RegisteredUserController::class, 'store'])->name('register');
        Route::delete('logout', [\App\Http\Controllers\Api\Dashboard\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});
