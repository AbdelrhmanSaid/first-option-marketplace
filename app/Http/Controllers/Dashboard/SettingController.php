<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Setting;
use App\Traits\CanUploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    use CanUploadFile;

    /**
     * Get the settings sections.
     */
    public function sections()
    {
        return [
            'application-information' => __('Application Information'),
            'theme-customizations' => __('Theme Customizations'),
            '3rd-party-services' => __('3rd Party Services'),
            'custom-code' => __('Custom Code'),
        ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('dashboard.settings.edit', [
            'sections' => $this->sections(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $keys = array_keys(config('redot.default_settings'));

        // Check if all validations pass
        $request->validate(config('redot.default_settings_validations'));

        foreach ($keys as $key) {
            $value = match (true) {
                $request->hasFile($key) => $this->uploadFile($request->file($key), 'settings'),
                is_bool(config("redot.default_settings.$key")) => $request->boolean($key),
                default => $request->input($key),
            };

            // Skip if the value is null
            if ($value === null) {
                continue;
            }

            // Update or create the setting
            Setting::set($key, $value);
        }

        // Flush the cache
        Artisan::call('optimize:clear');

        return $this->updated(__('Settings'));
    }
}
