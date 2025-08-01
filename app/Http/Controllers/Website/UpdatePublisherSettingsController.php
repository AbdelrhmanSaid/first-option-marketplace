<?php

namespace App\Http\Controllers\Website;

use App\Traits\CanUploadFile;
use Illuminate\Http\Request;

class UpdatePublisherSettingsController extends Controller
{
    use CanUploadFile;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
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

        current_publisher()->update($validated);

        return $this->success(__('Publisher settings updated successfully'));
    }
}
