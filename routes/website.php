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

    // Addon Purchase Routes
    Route::post('addons/{addon}/purchase', [\App\Http\Controllers\Website\AddonPurchaseController::class, 'purchase'])->name('addons.purchase');

    // Library Routes
    Route::get('library', [\App\Http\Controllers\Website\LibraryController::class, 'index'])->name('library.index');
    Route::get('library/{userAddon}', [\App\Http\Controllers\Website\LibraryController::class, 'show'])->name('library.show');

    // Subscription Management Routes
    Route::prefix('subscriptions')->as('subscriptions.')->group(function () {
        // Renewal routes
        Route::get('{userAddon}/renew', [\App\Http\Controllers\Website\SubscriptionController::class, 'showRenewal'])->name('renewal');
        Route::post('{userAddon}/renew', [\App\Http\Controllers\Website\SubscriptionController::class, 'processRenewal'])->name('renewal.process');
        Route::get('{userAddon}/payment', [\App\Http\Controllers\Website\SubscriptionController::class, 'showPayment'])->name('payment');
        Route::post('{userAddon}/payment', [\App\Http\Controllers\Website\SubscriptionController::class, 'processPayment'])->name('payment.process');
        Route::get('{userAddon}/success', [\App\Http\Controllers\Website\SubscriptionController::class, 'showSuccess'])->name('success');

        // Subscription management routes
        Route::post('{userAddon}/cancel', [\App\Http\Controllers\Website\SubscriptionController::class, 'cancel'])->name('cancel');
        Route::post('{userAddon}/reactivate', [\App\Http\Controllers\Website\SubscriptionController::class, 'reactivate'])->name('reactivate');

        // Trial conversion routes
        Route::get('{userAddon}/convert', [\App\Http\Controllers\Website\SubscriptionController::class, 'showTrialConversion'])->name('trial-conversion');
        Route::post('{userAddon}/convert', [\App\Http\Controllers\Website\SubscriptionController::class, 'processTrialConversion'])->name('trial-conversion.process');
        Route::get('{userAddon}/convert-payment', [\App\Http\Controllers\Website\SubscriptionController::class, 'showConversionPayment'])->name('conversion-payment');
        Route::post('{userAddon}/convert-payment', [\App\Http\Controllers\Website\SubscriptionController::class, 'processConversionPayment'])->name('conversion-payment.process');
        Route::get('{userAddon}/convert-success', [\App\Http\Controllers\Website\SubscriptionController::class, 'showConversionSuccess'])->name('conversion-success');
    });

    // Mock Payment Gateway Routes
    Route::get('payment/{addon}', [\App\Http\Controllers\Website\MockPaymentController::class, 'create'])->name('payment.create');
    Route::post('payment/{addon}/process', [\App\Http\Controllers\Website\MockPaymentController::class, 'process'])->name('payment.process');
    Route::get('payment/{addon}/success', [\App\Http\Controllers\Website\MockPaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('payment/{addon}/failure', [\App\Http\Controllers\Website\MockPaymentController::class, 'failure'])->name('payment.failure');

    // Subscription Payment Routes
    Route::get('payment/{addon}/subscription', [\App\Http\Controllers\Website\MockPaymentController::class, 'createSubscription'])->name('payment.subscription');
    Route::post('payment/{addon}/subscription', [\App\Http\Controllers\Website\MockPaymentController::class, 'processSubscription'])->name('payment.subscription.process');
    Route::get('payment/{addon}/subscription-success', [\App\Http\Controllers\Website\MockPaymentController::class, 'subscriptionSuccess'])->name('payment.subscription-success');
});

Route::prefix('publishers/dashboard')->as('publishers.dashboard.')->middleware(['auth:users', \App\Http\Middleware\Publisher::class])->group(function () {
    Route::get('{segment}', [\App\Http\Controllers\Website\PublisherDashboardController::class, 'index'])->name('index');
    Route::post('members', \App\Http\Controllers\Website\AddPublisherMemberController::class)->name('members.add');
    Route::delete('members/{publisherMember}', \App\Http\Controllers\Website\RemovePublisherMemberController::class)->name('members.remove');
    Route::put('settings', \App\Http\Controllers\Website\UpdatePublisherSettingsController::class)->name('settings.update');

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
