<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

Route::get('/', \App\Http\Controllers\Website\HomeController::class)->name('index');
Route::get('up', \App\Http\Controllers\Website\HealthCheckController::class)->name('health-check');
Route::get('r/{shortenedUrl?}', [\App\Http\Controllers\Website\ShortenedUrlController::class, 'show'])->name('shortened-urls.show');

Route::get('static-pages/{staticPage}', [\App\Http\Controllers\Website\StaticPageController::class, 'show'])->name('static-pages.show');
Route::resource('publishers', \App\Http\Controllers\Website\PublisherController::class)->only(['create', 'store', 'show']);

Route::middleware('auth:users')->group(function () {
    Route::get('profile', [\App\Http\Controllers\Website\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\Website\ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('publishers/dashboard')->as('publishers.dashboard.')->middleware(['auth:users', \App\Http\Middleware\Publisher::class])->group(function () {
    Route::get('{segment}', [\App\Http\Controllers\Website\PublisherDashboardController::class, 'index'])->name('index');
    Route::post('members', \App\Http\Controllers\Website\AddPublisherMemberController::class)->name('members.add');
    Route::delete('members/{publisherMember}', \App\Http\Controllers\Website\RemovePublisherMemberController::class)->name('members.remove');
    Route::put('settings', \App\Http\Controllers\Website\UpdatePublisherSettingsController::class)->name('settings.update');

    Route::resource('addons', \App\Http\Controllers\Website\PublisherAddonController::class)->only(['create', 'store']);
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register auth routes for your website.
|
*/

Route::middleware('guest:users')->group(function () {
    Route::get('login', [\App\Http\Controllers\Website\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [\App\Http\Controllers\Website\Auth\AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('register', [\App\Http\Controllers\Website\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [\App\Http\Controllers\Website\Auth\RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('forgot-password', [\App\Http\Controllers\Website\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [\App\Http\Controllers\Website\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [\App\Http\Controllers\Website\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [\App\Http\Controllers\Website\Auth\NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth:users')->group(function () {
    Route::get('verify-email', \App\Http\Controllers\Website\Auth\EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', \App\Http\Controllers\Website\Auth\VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [\App\Http\Controllers\Website\Auth\EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    Route::post('logout', [\App\Http\Controllers\Website\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
