<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\UserAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AddonPurchaseController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /**
     * Handle addon purchase based on type.
     */
    public function purchase(Request $request, Addon $addon)
    {
        $user = current_user();

        // Check if user already owns this addon
        if ($user->ownsAddon($addon)) {
            return back()->with('error', __('You already own this addon.'));
        }

        // Handle different addon types
        if ($addon->isFree()) {
            return $this->handleFreePurchase($user, $addon);
        }

        if ($addon->hasTrial()) {
            return $this->handleTrialPurchase($user, $addon);
        }

        if ($addon->isSubscription()) {
            return $this->handleSubscriptionPurchase($request, $user, $addon);
        }

        if ($addon->isPaid()) {
            return $this->handlePaidPurchase($user, $addon);
        }

        return back()->with('error', __('Invalid addon configuration.'));
    }

    /**
     * Handle free addon purchase.
     */
    protected function handleFreePurchase($user, Addon $addon)
    {
        try {
            UserAddon::createFreePurchase($user, $addon);

            return redirect()
                ->route('website.library.index')
                ->with('success', __('Free addon ":name" has been added to your library!', ['name' => $addon->name]));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to add addon to your library. Please try again.'));
        }
    }

    /**
     * Handle trial addon purchase.
     */
    protected function handleTrialPurchase($user, Addon $addon)
    {
        try {
            // Use subscription trial for subscription addons
            if ($addon->isSubscription()) {
                UserAddon::createTrialSubscription($user, $addon);
            } else {
                UserAddon::createTrialPurchase($user, $addon);
            }

            return redirect()
                ->route('website.library.index')
                ->with('success', __('Trial for ":name" has been started! You have :days days to try it.', [
                    'name' => $addon->name,
                    'days' => $addon->trial_period,
                ]));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to start trial. Please try again.'));
        }
    }

    /**
     * Handle subscription addon purchase.
     */
    protected function handleSubscriptionPurchase($request, $user, Addon $addon)
    {
        // Validate subscription period
        $request->validate([
            'period' => 'required|in:monthly,quarterly,yearly',
        ]);

        $period = $request->get('period');

        // Check if the addon supports this period
        if (!in_array($period, $addon->getAvailableSubscriptionPeriods())) {
            return back()->with('error', __('This subscription period is not available for this addon.'));
        }

        try {
            // Generate a unique payment reference
            $paymentReference = 'sub_' . Str::random(12);

            // Redirect to subscription payment gateway
            return redirect()->route('website.payment.subscription', [
                'addon' => $addon,
                'period' => $period,
                'reference' => $paymentReference,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to initiate subscription payment. Please try again.'));
        }
    }

    /**
     * Handle paid addon purchase.
     */
    protected function handlePaidPurchase($user, Addon $addon)
    {
        try {
            // Generate a unique payment reference
            $paymentReference = 'pay_' . Str::random(12);

            // Redirect to mock payment gateway
            return redirect()->route('website.payment.create', [
                'addon' => $addon,
                'reference' => $paymentReference,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to initiate payment. Please try again.'));
        }
    }
}
