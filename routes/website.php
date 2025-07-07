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
Route::resource('publishers', \App\Http\Controllers\Website\PublisherController::class)->only(['index', 'create', 'store', 'show']);
Route::resource('addons', \App\Http\Controllers\Website\AddonController::class)->only(['index', 'show']);

Route::middleware('auth:users')->group(function () {
    Route::get('profile', [\App\Http\Controllers\Website\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\Website\ProfileController::class, 'update'])->name('profile.update');

    Route::get('subscriptions', [\App\Http\Controllers\Website\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/{subscription}', [\App\Http\Controllers\Website\SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::get('subscribe/{addon}', [\App\Http\Controllers\Website\SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('subscribe/{addon}', [\App\Http\Controllers\Website\SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('subscribe/{subscription}/return', [\App\Http\Controllers\Website\SubscriptionController::class, 'return'])->name('subscriptions.return');
    Route::get('subscribe/{subscription}/download', [\App\Http\Controllers\Website\SubscriptionController::class, 'download'])->name('subscriptions.download');
    Route::post('subscribe/{subscription}/cancel', [\App\Http\Controllers\Website\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('subscribe/{subscription}/renew', [\App\Http\Controllers\Website\SubscriptionController::class, 'renew'])->name('subscriptions.renew');
    Route::post('rate/{addon}', [\App\Http\Controllers\Website\AddonRateController::class, 'store'])->name('rates.store');
    Route::put('rate/{addon}', [\App\Http\Controllers\Website\AddonRateController::class, 'update'])->name('rates.update');
});

Route::prefix('publishers/dashboard')->as('publishers.dashboard.')->middleware(['auth:users', \App\Http\Middleware\Publisher::class])->group(function () {
    Route::get('{segment}', [\App\Http\Controllers\Website\PublisherDashboardController::class, 'index'])->name('index');
    Route::post('members', \App\Http\Controllers\Website\AddPublisherMemberController::class)->name('members.add');
    Route::delete('members/{publisherMember}', \App\Http\Controllers\Website\RemovePublisherMemberController::class)->name('members.remove');
    Route::put('settings', \App\Http\Controllers\Website\UpdatePublisherSettingsController::class)->name('settings.update');

    // Addon approval/decline routes
    Route::patch('feedbacks/{addonRate}/approve', [\App\Http\Controllers\Website\PublisherAddonRateController::class, 'approve'])->name('feedbacks.approve');
    Route::patch('feedbacks/{addonRate}/decline', [\App\Http\Controllers\Website\PublisherAddonRateController::class, 'decline'])->name('feedbacks.decline');

    Route::resource('addons', \App\Http\Controllers\Website\PublisherAddonController::class)->only(['create', 'store', 'edit', 'update']);
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
