<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    /**
     * Get the label of the subscription status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::Active => __('Active'),
            self::Cancelled => __('Cancelled'),
            self::Expired => __('Expired'),
        };
    }

    /**
     * Get the color of the subscription status.
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Active => 'success',
            self::Cancelled => 'secondary',
            self::Expired => 'danger',
        };
    }
}
