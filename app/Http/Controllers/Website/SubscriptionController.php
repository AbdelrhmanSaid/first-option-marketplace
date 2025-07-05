<?php

namespace App\Http\Controllers\Website;

use App\Enums\SubscriptionStatus;
use App\Models\Addon;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = Subscription::with(['addon.publisher'])
            ->where('user_id', current_user()->id)
            ->latest('start_date')
            ->get();

        return view('website.subscriptions.index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Addon $addon)
    {
        return view('website.subscriptions.create', [
            'addon' => $addon,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Addon $addon)
    {
        $request->validate([
            'frequency' => 'required|integer|min:1|in:1,3,12',
        ]);

        $frequency = $request->frequency;
        $price = $addon->price * $frequency;

        if ($addon->trial_period) {
            $startDate = now();
            $endDate = now()->addDays($addon->trial_period);
        } else {
            $startDate = now();
            $endDate = now()->addMonths($frequency);
        }

        $subscription = Subscription::create([
            'user_id' => current_user()->id,
            'addon_id' => $addon->id,
            'frequency' => $frequency,
            'price' => $price,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => SubscriptionStatus::Pending,
        ]);

        // Payment gateway logic goes here

        return redirect()->route('website.subscriptions.return', $subscription);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        return view('website.subscriptions.show', [
            'subscription' => $subscription,
        ]);
    }

    /**
     * Return URL from the payment gateway.
     */
    public function return(Subscription $subscription)
    {
        $subscription->update([
            'status' => SubscriptionStatus::Active,
        ]);

        return $this->success(__('Subscription created successfully'), 'website.subscriptions.show', $subscription);
    }

    /**
     * Download the subscription.
     */
    public function download(Subscription $subscription)
    {
        return view('website.subscriptions.download', [
            'subscription' => $subscription,
        ]);
    }

    /**
     * Cancel the subscription.
     */
    public function cancel(Subscription $subscription)
    {
        $subscription->update([
            'status' => SubscriptionStatus::Cancelled,
        ]);

        return $this->success(__('Subscription cancelled successfully'), 'website.subscriptions.index');
    }

    /**
     * Renew the subscription.
     */
    public function renew(Subscription $subscription)
    {
        $subscription->update([
            'status' => SubscriptionStatus::Active,
        ]);

        return $this->success(__('Subscription renewed successfully'), 'website.subscriptions.index');
    }
}
