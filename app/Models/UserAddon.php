<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'addon_id',
        'price_paid',
        'expires_at',
        'is_trial',
        'subscription_period',
        'next_billing_date',
        'auto_renew',
        'grace_period_ends_at',
        'payment_reference',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_paid' => 'decimal:2',
        'expires_at' => 'datetime',
        'is_trial' => 'boolean',
        'next_billing_date' => 'datetime',
        'auto_renew' => 'boolean',
        'grace_period_ends_at' => 'datetime',
    ];

    /**
     * Get the user that owns the addon.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the addon that belongs to the user.
     */
    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }

    /**
     * Scope a query to only include active addons.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope a query to only include expired addons.
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'expired')
                ->orWhere(function ($subq) {
                    $subq->where('status', 'active')
                        ->whereNotNull('expires_at')
                        ->where('expires_at', '<=', now());
                });
        });
    }

    /**
     * Scope a query to only include trial addons.
     */
    public function scopeTrial($query)
    {
        return $query->where('is_trial', true);
    }

    /**
     * Check if the addon is currently active.
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            // Check if in grace period for subscriptions
            if ($this->isSubscription() && $this->isInGracePeriod()) {
                return true;
            }
            return false;
        }

        // For subscriptions, check billing status
        if ($this->isSubscription()) {
            return true; // Active subscriptions are always active (even if billing is due)
        }

        // For one-time purchases and trials, check expiration
        if ($this->expires_at && $this->expires_at <= now()) {
            return false;
        }

        return true;
    }

    /**
     * Check if the addon is expired.
     */
    public function isExpired(): bool
    {
        return ! $this->isActive();
    }

    /**
     * Get the expiration status for display.
     */
    public function getExpirationStatusAttribute(): string
    {
        if (! $this->expires_at) {
            return 'Never';
        }

        if ($this->isExpired()) {
            return 'Expired';
        }

        return $this->expires_at->format('M j, Y');
    }

    /**
     * Mark the addon as expired.
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Check if this is a subscription.
     */
    public function isSubscription(): bool
    {
        return in_array($this->subscription_period, ['monthly', 'quarterly', 'yearly']);
    }

    /**
     * Check if subscription is in grace period.
     */
    public function isInGracePeriod(): bool
    {
        return $this->grace_period_ends_at && $this->grace_period_ends_at > now();
    }

    /**
     * Check if subscription needs renewal.
     */
    public function needsRenewal(): bool
    {
        return $this->isSubscription() &&
               $this->next_billing_date &&
               $this->next_billing_date <= now() &&
               $this->auto_renew &&
               $this->status === 'active';
    }

    /**
     * Calculate next billing date based on subscription period.
     */
    public function calculateNextBillingDate(): ?\Carbon\Carbon
    {
        if (!$this->isSubscription()) {
            return null;
        }

        $baseDate = $this->next_billing_date ?? now();

        return match($this->subscription_period) {
            'monthly' => $baseDate->addMonth(),
            'quarterly' => $baseDate->addMonths(3),
            'yearly' => $baseDate->addYear(),
            default => null,
        };
    }

    /**
     * Start grace period for expired subscription.
     */
    public function startGracePeriod(): void
    {
        $this->update([
            'status' => 'expired',
            'grace_period_ends_at' => now()->addDays(7), // 7-day grace period
        ]);
    }

    /**
     * Renew the subscription.
     */
    public function renew(string $paymentReference): void
    {
        $this->update([
            'status' => 'active',
            'next_billing_date' => $this->calculateNextBillingDate(),
            'payment_reference' => $paymentReference,
            'grace_period_ends_at' => null,
        ]);
    }

    /**
     * Cancel subscription (turn off auto-renewal).
     */
    public function cancelSubscription(): void
    {
        $this->update(['auto_renew' => false]);
    }

    /**
     * Reactivate subscription (turn on auto-renewal).
     */
    public function reactivateSubscription(): void
    {
        $this->update(['auto_renew' => true]);
    }

    /**
     * Convert trial to subscription.
     */
    public function convertTrialToSubscription(string $period, string $paymentReference): void
    {
        $nextBilling = match($period) {
            'monthly' => now()->addMonth(),
            'quarterly' => now()->addMonths(3),
            'yearly' => now()->addYear(),
            default => now()->addMonth(),
        };

        $this->update([
            'is_trial' => false,
            'subscription_period' => $period,
            'next_billing_date' => $nextBilling,
            'auto_renew' => true,
            'payment_reference' => $paymentReference,
            'price_paid' => $this->addon->getSubscriptionPrice($period),
            'expires_at' => null, // Subscriptions don't use expires_at
            'status' => 'active',
        ]);
    }

    /**
     * Create a purchase record for a free addon.
     */
    public static function createFreePurchase(User $user, Addon $addon): self
    {
        return self::create([
            'user_id' => $user->id,
            'addon_id' => $addon->id,
            'price_paid' => 0,
            'is_trial' => false,
            'status' => 'active',
        ]);
    }

    /**
     * Create a purchase record for a trial addon.
     */
    public static function createTrialPurchase(User $user, Addon $addon): self
    {
        return self::create([
            'user_id' => $user->id,
            'addon_id' => $addon->id,
            'price_paid' => 0,
            'expires_at' => now()->addDays($addon->trial_period),
            'is_trial' => true,
            'status' => 'active',
        ]);
    }

    /**
     * Create a purchase record for a paid addon.
     */
    public static function createPaidPurchase(User $user, Addon $addon, string $paymentReference): self
    {
        return self::create([
            'user_id' => $user->id,
            'addon_id' => $addon->id,
            'price_paid' => $addon->price,
            'is_trial' => false,
            'payment_reference' => $paymentReference,
            'status' => 'active',
        ]);
    }

    /**
     * Create a subscription purchase record.
     */
    public static function createSubscriptionPurchase(User $user, Addon $addon, string $period, string $paymentReference): self
    {
        $price = $addon->getSubscriptionPrice($period);
        $nextBilling = match($period) {
            'monthly' => now()->addMonth(),
            'quarterly' => now()->addMonths(3),
            'yearly' => now()->addYear(),
            default => now()->addMonth(),
        };

        return self::create([
            'user_id' => $user->id,
            'addon_id' => $addon->id,
            'price_paid' => $price,
            'is_trial' => false,
            'subscription_period' => $period,
            'next_billing_date' => $nextBilling,
            'auto_renew' => true,
            'payment_reference' => $paymentReference,
            'status' => 'active',
        ]);
    }

    /**
     * Create a trial subscription record (for subscription addons).
     */
    public static function createTrialSubscription(User $user, Addon $addon): self
    {
        return self::create([
            'user_id' => $user->id,
            'addon_id' => $addon->id,
            'price_paid' => 0,
            'expires_at' => now()->addDays($addon->trial_period),
            'is_trial' => true,
            'subscription_period' => 'monthly', // Default for trial conversion
            'auto_renew' => false, // Will be enabled after trial conversion
            'status' => 'active',
        ]);
    }
}
