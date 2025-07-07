<?php

namespace App\Policies;

use App\Models\Addon;
use App\Models\AddonRate;
use App\Models\User;

class AddonRatePolicy
{
    /**
     * Determine whether the user can create a rating.
     */
    public function create(User $user, Addon $addon): bool
    {
        return $user->hasActiveSubscription($addon);
    }

    /**
     * Determine whether the user can update the rating.
     */
    public function update(User $user, AddonRate $rate): bool
    {
        return $rate->user_id === $user->id;
    }
}
