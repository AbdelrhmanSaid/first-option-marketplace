<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\UserAddon;
use Illuminate\Http\Request;

class MockPaymentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /**
     * Generate payment link (mock).
     */
    public function create(Request $request, Addon $addon)
    {
        $user = current_user();
        $reference = $request->get('reference');

        // Check if user already owns this addon
        if ($user->ownsAddon($addon)) {
            return redirect()
                ->route('website.library.index')
                ->with('error', __('You already own this addon.'));
        }

        // Check if addon is actually paid
        if (! $addon->isPaid()) {
            return redirect()
                ->route('website.addons.show', $addon)
                ->with('error', __('This addon is not available for purchase.'));
        }

        return view('website.payment.create', [
            'addon' => $addon,
            'reference' => $reference,
            'user' => $user,
        ]);
    }

    /**
     * Process payment (mock).
     */
    public function process(Request $request, Addon $addon)
    {
        $request->validate([
            'reference' => 'required|string',
            'action' => 'required|in:pay,cancel',
        ]);

        $user = current_user();
        $reference = $request->get('reference');

        // If user cancelled payment
        if ($request->get('action') === 'cancel') {
            return redirect()
                ->route('website.addons.show', $addon)
                ->with('info', __('Payment was cancelled.'));
        }

        // Check if user already owns this addon
        if ($user->ownsAddon($addon)) {
            return redirect()
                ->route('website.library.index')
                ->with('error', __('You already own this addon.'));
        }

        try {
            // Create the purchase record
            UserAddon::createPaidPurchase($user, $addon, $reference);

            // Simulate payment processing delay
            sleep(1);

            // Redirect to success page
            return redirect()->route('website.payment.success', [
                'addon' => $addon,
                'reference' => $reference,
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('website.addons.show', $addon)
                ->with('error', __('Payment failed. Please try again.'));
        }
    }

    /**
     * Payment success page.
     */
    public function paymentSuccess(Request $request, Addon $addon)
    {
        $reference = $request->get('reference');
        $user = current_user();

        // Verify that the user actually has this addon
        $userAddon = $user->userAddons()
            ->where('addon_id', $addon->id)
            ->where('payment_reference', $reference)
            ->first();

        if (! $userAddon) {
            return redirect()
                ->route('website.addons.show', $addon)
                ->with('error', __('Payment verification failed.'));
        }

        return view('website.payment.success', [
            'addon' => $addon,
            'reference' => $reference,
            'userAddon' => $userAddon,
        ]);
    }

    /**
     * Payment failure page.
     */
    public function failure(Request $request, Addon $addon)
    {
        $reference = $request->get('reference');

        return view('website.payment.failure', [
            'addon' => $addon,
            'reference' => $reference,
        ]);
    }

    /**
     * Generate subscription payment link (mock).
     */
    public function createSubscription(Request $request, Addon $addon)
    {
        $user = current_user();
        $reference = $request->get('reference');
        $period = $request->get('period');

        // Check if user already owns this addon
        if ($user->ownsAddon($addon)) {
            return redirect()
                ->route('website.library.index')
                ->with('error', __('You already own this addon.'));
        }

        // Check if addon supports subscriptions
        if (!$addon->isSubscription()) {
            return redirect()
                ->route('website.addons.show', $addon)
                ->with('error', __('This addon does not support subscriptions.'));
        }

        // Validate period
        if (!in_array($period, $addon->getAvailableSubscriptionPeriods())) {
            return redirect()
                ->route('website.addons.show', $addon)
                ->with('error', __('Invalid subscription period.'));
        }

        return view('website.payment.subscription', [
            'addon' => $addon,
            'period' => $period,
            'reference' => $reference,
            'user' => $user,
            'amount' => $addon->getSubscriptionPrice($period),
        ]);
    }

    /**
     * Process subscription payment (mock).
     */
    public function processSubscription(Request $request, Addon $addon)
    {
        $request->validate([
            'period' => 'required|in:monthly,quarterly,yearly',
            'reference' => 'required|string',
            'action' => 'required|in:pay,cancel',
        ]);

        $user = current_user();
        $reference = $request->get('reference');
        $period = $request->get('period');

        // If user cancelled payment
        if ($request->get('action') === 'cancel') {
            return redirect()
                ->route('website.addons.show', $addon)
                ->with('info', __('Payment was cancelled.'));
        }

        // Check if user already owns this addon
        if ($user->ownsAddon($addon)) {
            return redirect()
                ->route('website.library.index')
                ->with('error', __('You already own this addon.'));
        }

        try {
            // Create the subscription purchase record
            UserAddon::createSubscriptionPurchase($user, $addon, $period, $reference);

            // Simulate payment processing delay
            sleep(1);

            // Redirect to success page
            return redirect()->route('website.payment.subscription-success', [
                'addon' => $addon,
                'reference' => $reference,
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('website.addons.show', $addon)
                ->with('error', __('Subscription payment failed. Please try again.'));
        }
    }

    /**
     * Subscription payment success page.
     */
    public function subscriptionSuccess(Request $request, Addon $addon)
    {
        $reference = $request->get('reference');
        $user = current_user();

        // Verify that the user actually has this subscription
        $userAddon = $user->userAddons()
            ->where('addon_id', $addon->id)
            ->where('payment_reference', $reference)
            ->first();

        if (! $userAddon) {
            return redirect()
                ->route('website.addons.show', $addon)
                ->with('error', __('Payment verification failed.'));
        }

        return view('website.payment.subscription-success', [
            'addon' => $addon,
            'reference' => $reference,
            'userAddon' => $userAddon,
        ]);
    }
}
