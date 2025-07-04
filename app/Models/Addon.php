<?php

namespace App\Models;

use App\Enums\OS;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Addon extends Model
{
    use Taggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'publisher_id',
        'software_id',
        'category_id',
        'discipline_id',
        'slug',
        'name',
        'short_description',
        'description',
        'instructions',
        'icon',
        'price',
        'subscription_type',
        'monthly_price',
        'quarterly_price',
        'yearly_price',
        'trial_period',
        'screenshots',
        'youtube_video_url',
        'os',
        'privacy_policy_url',
        'terms_of_service_url',
        'learn_more_url',
        'is_active',
        'tags',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'screenshots' => 'array',
        'tags' => 'array',
        'os' => OS::class,
        'is_active' => 'boolean',
        'monthly_price' => 'decimal:2',
        'quarterly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($addon) {
            $addon->slug = static::generateUniqueSlug($addon->name);
        });
    }

    /**
     * Generate a unique slug for the addon.
     */
    protected static function generateUniqueSlug(string $name): string
    {
        $slug = sprintf('addon-%s-%s', Str::slug($name), Str::random(5));

        if (static::where('slug', $slug)->exists()) {
            return static::generateUniqueSlug($name);
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the publisher that owns the addon.
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * Get the software that owns the addon.
     */
    public function software(): BelongsTo
    {
        return $this->belongsTo(Software::class);
    }

    /**
     * Get the category that owns the addon.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the discipline that owns the addon.
     */
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    /**
     * Get the versions of the addon.
     */
    public function versions(): HasMany
    {
        return $this->hasMany(AddonVersion::class);
    }

    /**
     * Get the user purchases of this addon.
     */
    public function userAddons(): HasMany
    {
        return $this->hasMany(UserAddon::class);
    }

    /**
     * Check if the addon is free.
     */
    public function isFree(): bool
    {
        return ! $this->price || $this->price == 0;
    }

    /**
     * Check if the addon has a trial period.
     */
    public function hasTrial(): bool
    {
        return $this->trial_period && $this->trial_period > 0;
    }

    /**
     * Check if the addon is paid.
     */
    public function isPaid(): bool
    {
        return $this->price && $this->price > 0;
    }

    /**
     * Check if the addon supports subscriptions.
     */
    public function isSubscription(): bool
    {
        return $this->subscription_type === 'subscription';
    }

    /**
     * Check if the addon is one-time purchase.
     */
    public function isOneTime(): bool
    {
        return $this->subscription_type === 'one_time';
    }

    /**
     * Get the price for a specific subscription period.
     */
    public function getSubscriptionPrice(string $period): ?float
    {
        return match($period) {
            'monthly' => $this->monthly_price,
            'quarterly' => $this->quarterly_price,
            'yearly' => $this->yearly_price,
            'one_time' => $this->price,
            default => null,
        };
    }

    /**
     * Get available subscription periods for this addon.
     */
    public function getAvailableSubscriptionPeriods(): array
    {
        if ($this->isOneTime()) {
            return ['one_time'];
        }

        $periods = [];
        if ($this->monthly_price > 0) $periods[] = 'monthly';
        if ($this->quarterly_price > 0) $periods[] = 'quarterly';
        if ($this->yearly_price > 0) $periods[] = 'yearly';

        return $periods;
    }

    /**
     * Get formatted subscription price.
     */
    public function getFormattedSubscriptionPrice(string $period): string
    {
        $price = $this->getSubscriptionPrice($period);

        if (!$price) {
            return 'Not Available';
        }

        $formatted = sprintf('$%.2f', $price);

        return match($period) {
            'monthly' => $formatted . '/month',
            'quarterly' => $formatted . '/3 months',
            'yearly' => $formatted . '/year',
            'one_time' => $formatted,
            default => $formatted,
        };
    }

    /**
     * Get the best value subscription period (lowest monthly cost).
     */
    public function getBestValuePeriod(): string
    {
        if ($this->isOneTime()) {
            return 'one_time';
        }

        $periods = $this->getAvailableSubscriptionPeriods();
        $bestPeriod = null;
        $lowestMonthlyCost = null;

        foreach ($periods as $period) {
            $price = $this->getSubscriptionPrice($period);
            if (!$price) continue;

            $monthlyCost = match($period) {
                'monthly' => $price,
                'quarterly' => $price / 3,
                'yearly' => $price / 12,
                default => $price,
            };

            if ($lowestMonthlyCost === null || $monthlyCost < $lowestMonthlyCost) {
                $lowestMonthlyCost = $monthlyCost;
                $bestPeriod = $period;
            }
        }

        return $bestPeriod ?? 'monthly';
    }

    /**
     * Get the addon type for display.
     */
    public function getTypeAttribute(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }

        if ($this->hasTrial()) {
            return sprintf('Trial (%d days)', $this->trial_period);
        }

        if ($this->isSubscription()) {
            $bestPeriod = $this->getBestValuePeriod();
            return 'Subscription - ' . $this->getFormattedSubscriptionPrice($bestPeriod);
        }

        return sprintf('$%.2f', $this->price);
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }

        if ($this->isSubscription()) {
            $bestPeriod = $this->getBestValuePeriod();
            return $this->getFormattedSubscriptionPrice($bestPeriod);
        }

        return sprintf('$%.2f', $this->price);
    }

    /**
     * Scope a query to only include featured addons.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_active', true)->orderBy('created_at', 'desc');
    }
}
