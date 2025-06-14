<?php

use App\Http\Middleware\Dashboard\Locked;
use App\Http\Middleware\Dashboard\RoutePermission;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register dashboard routes for your application.
|
*/

Route::middleware('auth:admins')->group(function () {
    Route::get('/', \App\Http\Controllers\Dashboard\DashboardController::class)->name('index')->withoutMiddleware(RoutePermission::class);

    /* --------- Website Management --------- */
    Route::resource('users', \App\Http\Controllers\Dashboard\UserController::class);
    Route::resource('static-pages', \App\Http\Controllers\Dashboard\StaticPageController::class)->only(['index', 'edit', 'update']);

    /* --------- Listing Management --------- */
    Route::resource('software', \App\Http\Controllers\Dashboard\SoftwareController::class)->except(['show']);

    /* --------- Utilities --------- */
    Route::withoutMiddleware(RoutePermission::class)->group(function () {
        Route::resource('shortened-urls', \App\Http\Controllers\Dashboard\ShortenedUrlController::class)->except(['show']);
        Route::resource('memos', \App\Http\Controllers\Dashboard\MemoController::class);
        Route::resource('qr-code', \App\Http\Controllers\Dashboard\QrCodeController::class)->only(['index']);
    });

    Route::get('impersonate', [\App\Http\Controllers\Dashboard\ImpersonateController::class, 'create'])->name('impersonate.create');
    Route::post('impersonate', [\App\Http\Controllers\Dashboard\ImpersonateController::class, 'store'])->name('impersonate.store');

    /* --------- Settings --------- */
    Route::withoutMiddleware(RoutePermission::class)->group(function () {
        Route::get('profile', [\App\Http\Controllers\Dashboard\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [\App\Http\Controllers\Dashboard\ProfileController::class, 'update'])->name('profile.update');
    });

    Route::resource('roles', \App\Http\Controllers\Dashboard\RoleController::class)->except(['show']);
    Route::resource('admins', \App\Http\Controllers\Dashboard\AdminController::class)->except(['show']);

    Route::get('settings', [\App\Http\Controllers\Dashboard\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [\App\Http\Controllers\Dashboard\SettingController::class, 'update'])->name('settings.update');

    Route::resource('languages', \App\Http\Controllers\Dashboard\LanguageController::class)->except(['show']);
    Route::resource('languages.tokens', \App\Http\Controllers\Dashboard\LanguageTokenController::class)->only(['index', 'edit', 'update']);
    Route::get('languages/{language}/tokens/{token}/translate', \App\Http\Controllers\Dashboard\TranslateLanguageTokenController::class)->name('languages.tokens.translate');
    Route::get('languages/{language}/tokens/extract', \App\Http\Controllers\Dashboard\ExtractLanguageTokensController::class)->name('languages.tokens.extract');
    Route::get('languages/{language}/tokens/publish', \App\Http\Controllers\Dashboard\PublishLanguageTokensController::class)->name('languages.tokens.publish');
    Route::get('languages/{language}/tokens/revert', \App\Http\Controllers\Dashboard\RevertLanguageTokensController::class)->name('languages.tokens.revert');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register auth routes for your dashboard.
|
*/

Route::withoutMiddleware(RoutePermission::class)->group(function () {
    Route::middleware('guest:admins')->group(function () {
        Route::get('login', [\App\Http\Controllers\Dashboard\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [\App\Http\Controllers\Dashboard\Auth\AuthenticatedSessionController::class, 'store'])->name('login.store');

        Route::get('forgot-password', [\App\Http\Controllers\Dashboard\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [\App\Http\Controllers\Dashboard\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

        Route::get('reset-password/{token}', [\App\Http\Controllers\Dashboard\Auth\NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [\App\Http\Controllers\Dashboard\Auth\NewPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('auth:admins')->group(function () {
        Route::post('lock', [\App\Http\Controllers\Dashboard\Auth\LockController::class, 'store'])->name('lock');

        Route::withoutMiddleware(Locked::class)->group(function () {
            Route::get('unlock', [\App\Http\Controllers\Dashboard\Auth\UnlockController::class, 'create'])->name('unlock');
            Route::post('unlock', [\App\Http\Controllers\Dashboard\Auth\UnlockController::class, 'store'])->name('unlock.store');
            Route::post('logout', [\App\Http\Controllers\Dashboard\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
        });
    });
});
