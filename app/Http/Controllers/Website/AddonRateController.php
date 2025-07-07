<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\AddonRate;
use Illuminate\Http\Request;

class AddonRateController extends Controller
{
    /**
     * Store a newly created rating in storage.
     */
    public function store(Request $request, Addon $addon)
    {
        $this->authorize('create', [AddonRate::class, $addon]);

        $data = $request->validate([
            'rate' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string'],
        ]);

        $data['user_id'] = current_user()->id;
        $data['addon_id'] = $addon->id;
        $data['is_approved'] = null;

        $rate = AddonRate::updateOrCreate(
            ['addon_id' => $addon->id, 'user_id' => current_user()->id],
            $data,
        );

        return $this->success(__('Rating saved successfully.'));
    }

    /**
     * Update the specified rating in storage.
     */
    public function update(Request $request, Addon $addon)
    {
        $rate = $addon->rates()->where('user_id', current_user()->id)->firstOrFail();
        $this->authorize('update', $rate);

        $data = $request->validate([
            'rate' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string'],
        ]);

        $rate->update($data);

        return $this->success(__('Rating updated successfully.'));
    }
}
