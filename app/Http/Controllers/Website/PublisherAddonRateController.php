<?php

namespace App\Http\Controllers\Website;

use App\Models\AddonRate;

class PublisherAddonRateController extends Controller
{
    /**
     * Approve the given addon rate.
     */
    public function approve(AddonRate $addonRate)
    {
        $this->authorizeRate($addonRate);

        $addonRate->update(['is_approved' => true]);

        return $this->success(__('Rating approved successfully.'));
    }

    /**
     * Decline the given addon rate.
     */
    public function decline(AddonRate $addonRate)
    {
        $this->authorizeRate($addonRate);

        $addonRate->update(['is_approved' => false]);

        return $this->success(__('Rating declined successfully.'));
    }

    /**
     * Ensure the rate belongs to the current publisher.
     */
    protected function authorizeRate(AddonRate $addonRate): void
    {
        $publisher = current_publisher();

        if (! $publisher || $addonRate->addon->publisher_id !== $publisher->id) {
            abort(403);
        }
    }
}
