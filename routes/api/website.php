<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your website.
|
*/

Route::get('/', [\App\Http\Controllers\Api\Website\HomeController::class, 'index'])->name('index');

Route::middleware('auth:users-api')->group(function () {
    Route::get('profile', [\App\Http\Controllers\Api\Website\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\Api\Website\ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register auth routes for your website.
|
*/

Route::prefix('auth')->group(function () {
    Route::middleware('guest:users-api')->group(function () {
        Route::post('register', [\App\Http\Controllers\Api\Website\Auth\RegisteredUserController::class, 'store'])->name('register');
        Route::post('login', [\App\Http\Controllers\Api\Website\Auth\AuthenticatedSessionController::class, 'store'])->name('login');
        Route::post('forgot-password', [\App\Http\Controllers\Api\Website\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::post('reset-password', [\App\Http\Controllers\Api\Website\Auth\NewPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('auth:users-api')->group(function () {
        Route::get('verify-email/{id}/{hash}', \App\Http\Controllers\Api\Website\Auth\VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
        Route::post('email/verification-notification', [\App\Http\Controllers\Api\Website\Auth\EmailVerificationNotificationController::class, 'store'])->middleware(['throttle:6,1'])->name('verification.send');
        Route::delete('logout', [\App\Http\Controllers\Api\Website\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});
