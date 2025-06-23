<?php

namespace App\Policies;

use App\Models\Addon;
use App\Models\User;

class AddonPolicy
{
    /**
     * Determine whether the user can update the addon.
     */
    public function update(User $user, Addon $addon): bool
    {
        return $user->publisher && $addon->publisher_id === $user->publisher->id;
    }
}
