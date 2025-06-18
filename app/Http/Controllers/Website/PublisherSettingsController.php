<?php

namespace App\Http\Controllers\Website;

use App\Traits\CanUploadFile;
use Illuminate\Http\Request;

class PublisherSettingsController extends Controller
{
    use CanUploadFile;

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'headline' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->uploadFile($request->file('logo'), 'publishers');
        }

        current_user()->publisher->update($validated);

        return $this->success(__('Publisher settings updated successfully'));
    }
}
