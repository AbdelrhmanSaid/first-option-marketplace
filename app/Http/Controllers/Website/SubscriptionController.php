<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\UserAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /**
     * Show subscription renewal page.
     */
    public function showRenewal(UserAddon $userAddon)
    {
        $user = current_user();

        // Verify ownership
        if ($userAddon->user_id !== $user->id) {
            abort(403);
        }

        // Check if addon needs renewal or is in grace period
        if (!$userAddon->needsRenewal() && !$userAddon->isInGracePeriod()) {
            return redirect()
                ->route('website.library.show', $userAddon)
                ->with('info', __('This subscription does not need renewal at this time.'));
        }

        return view('website.subscriptions.renewal', [
            'userAddon' => $userAddon,
            'addon' => $userAddon->addon,
        ]);
    }

    /**
     * Process subscription renewal.
     */
    public function processRenewal(Request $request, UserAddon $userAddon)
    {
        $request->validate([
            'action' => 'required|in:renew,cancel',
        ]);

        $user = current_user();

        // Verify ownership
        if ($userAddon->user_id !== $user->id) {
            abort(403);
        }

        if ($request->action === 'cancel') {
            return redirect()
                ->route('website.library.show', $userAddon)
                ->with('info', __('Renewal cancelled.'));
        }

        try {
            // Generate payment reference
            $paymentReference = 'renew_' . Str::random(12);

            // Redirect to payment gateway for renewal
            return redirect()->route('website.subscriptions.payment', [
                'userAddon' => $userAddon,
                'reference' => $paymentReference,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to process renewal. Please try again.'));
        }
    }

    /**
     * Show subscription payment page.
     */
    public function showPayment(Request $request, UserAddon $userAddon)
    {
        $user = current_user();
        $reference = $request->get('reference');

        // Verify ownership
        if ($userAddon->user_id !== $user->id) {
            abort(403);
        }

        return view('website.subscriptions.payment', [
            'userAddon' => $userAddon,
            'addon' => $userAddon->addon,
            'reference' => $reference,
            'amount' => $userAddon->addon->getSubscriptionPrice($userAddon->subscription_period),
        ]);
    }

    /**
     * Process subscription payment.
     */
    public function processPayment(Request $request, UserAddon $userAddon)
    {
        $request->validate([
            'reference' => 'required|string',
            'action' => 'required|in:pay,cancel',
        ]);

        $user = current_user();
        $reference = $request->get('reference');

        // Verify ownership
        if ($userAddon->user_id !== $user->id) {
            abort(403);
        }

        if ($request->action === 'cancel') {
            return redirect()
                ->route('website.library.show', $userAddon)
                ->with('info', __('Payment cancelled.'));
        }

        try {
            // Process renewal
            $userAddon->renew($reference);

            return redirect()->route('website.subscriptions.success', [
                'userAddon' => $userAddon,
                'reference' => $reference,
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('website.library.show', $userAddon)
                ->with('error', __('Payment failed. Please try again.'));
        }
    }

    /**
     * Show renewal success page.
     */
    public function showSuccess(Request $request, UserAddon $userAddon)
    {
        $reference = $request->get('reference');
        $user = current_user();

        // Verify ownership and payment
        if ($userAddon->user_id !== $user->id || $userAddon->payment_reference !== $reference) {
            abort(403);
        }

        return view('website.subscriptions.success', [
            'userAddon' => $userAddon,
            'addon' => $userAddon->addon,
            'reference' => $reference,
        ]);
    }

    /**
     * Cancel subscription (turn off auto-renewal).
     */
    public function cancel(UserAddon $userAddon)
    {
        $user = current_user();

        // Verify ownership
        if ($userAddon->user_id !== $user->id) {
            abort(403);
        }

        $userAddon->cancelSubscription();

        return back()->with('success', __('Subscription cancelled. Auto-renewal has been turned off.'));
    }

    /**
     * Reactivate subscription (turn on auto-renewal).
     */
    public function reactivate(UserAddon $userAddon)
    {
        $user = current_user();

        // Verify ownership
        if ($userAddon->user_id !== $user->id) {
            abort(403);
        }

        $userAddon->reactivateSubscription();

        return back()->with('success', __('Subscription reactivated. Auto-renewal has been turned on.'));
    }

    /**
     * Show trial conversion page.
     */
    public function showTrialConversion(UserAddon $userAddon)
    {
        $user = current_user();

        // Verify ownership
        if ($userAddon->user_id !== $user->id) {
            abort(403);
        }

        // Check if it's a trial
        if (!$userAddon->is_trial) {
            return redirect()
                ->route('website.library.show', $userAddon)
                ->with('error', __('This is not a trial subscription.'));
        }

        return view('website.subscriptions.trial-conversion', [
            'userAddon' => $userAddon,
            'addon' => $userAddon->addon,
            'availablePeriods' => $userAddon->addon->getAvailableSubscriptionPeriods(),
        ]);
    }

    /**
     * Process trial conversion.
     */
    public function processTrialConversion(Request $request, UserAddon $userAddon)
    {
        $request->validate([
            'period' => 'required|in:monthly,quarterly,yearly',
        ]);

        $user = current_user();

        // Verify ownership
        if ($userAddon->user_id !== $user->id) {
            abort(403);
        }

        // Check if it's a trial
        if (!$userAddon->is_trial) {
            return back()->with('error', __('This is not a trial subscription.'));
        }

        try {
            // Generate payment reference
            $paymentReference = 'convert_' . Str::random(12);

            // Redirect to payment gateway for conversion
            return redirect()->route('website.subscriptions.conversion-payment', [
                'userAddon' => $userAddon,
                'period' => $request->period,
                'reference' => $paymentReference,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to process conversion. Please try again.'));
        }
    }

    /**
     * Show trial conversion payment page.
     */
    public function showConversionPayment(Request $request, UserAddon $userAddon)
    {
        $user = current_user();
        $period = $request->get('period');
        $reference = $request->get('reference');

        // Verify ownership
        if ($userAddon->user_id !== $user->id) {
            abort(403);
        }

        return view('website.subscriptions.conversion-payment', [
            'userAddon' => $userAddon,
            'addon' => $userAddon->addon,
            'period' => $period,
            'reference' => $reference,
            'amount' => $userAddon->addon->getSubscriptionPrice($period),
        ]);
    }

    /**
     * Process trial conversion payment.
     */
    public function processConversionPayment(Request $request, UserAddon $userAddon)
    {
        $request->validate([
            'period' => 'required|in:monthly,quarterly,yearly',
            'reference' => 'required|string',
            'action' => 'required|in:pay,cancel',
        ]);

        $user = current_user();

        // Verify ownership
        if ($userAddon->user_id !== $user->id) {
            abort(403);
        }

        if ($request->action === 'cancel') {
            return redirect()
                ->route('website.library.show', $userAddon)
                ->with('info', __('Trial conversion cancelled.'));
        }

        try {
            // Convert trial to subscription
            $userAddon->convertTrialToSubscription($request->period, $request->reference);

            return redirect()->route('website.subscriptions.conversion-success', [
                'userAddon' => $userAddon,
                'reference' => $request->reference,
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('website.library.show', $userAddon)
                ->with('error', __('Conversion failed. Please try again.'));
        }
    }

    /**
     * Show trial conversion success page.
     */
    public function showConversionSuccess(Request $request, UserAddon $userAddon)
    {
        $reference = $request->get('reference');
        $user = current_user();

        // Verify ownership and payment
        if ($userAddon->user_id !== $user->id || $userAddon->payment_reference !== $reference) {
            abort(403);
        }

        return view('website.subscriptions.conversion-success', [
            'userAddon' => $userAddon,
            'addon' => $userAddon->addon,
            'reference' => $reference,
        ]);
    }
}
