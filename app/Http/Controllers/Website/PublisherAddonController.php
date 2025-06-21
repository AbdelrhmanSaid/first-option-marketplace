<?php

namespace App\Http\Controllers\Website;

use App\Enums\OS;
use App\Models\Addon;
use App\Traits\CanUploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublisherAddonController extends Controller
{
    use CanUploadFile;

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('website.publishers.dashboard.addons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'icon' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'name' => 'required|string',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'screenshots' => 'nullable|json',
            'software_id' => 'required|exists:software,id',
            'os' => 'required|in:' . implode(',', array_keys(OS::toArray())),
            'version' => 'required|string',
            'resource' => 'required|file|mimes:zip,rar,tar,gz,tgz,pdf,msi',
            'instructions' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'trial_period' => 'nullable|integer|min:0',
            'youtube_video_url' => 'nullable|url',
            'privacy_policy_url' => 'nullable|url',
            'terms_of_service_url' => 'nullable|url',
            'learn_more_url' => 'nullable|url',
        ]);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $this->uploadFile($request->file('icon'), 'addons-icons');
        }

        if ($request->hasFile('resource')) {
            $validated['resource'] = $this->uploadFile($request->file('resource'), 'addons-resources');
        }

        $addon = Addon::create(collect($validated)->except(['version', 'resource'])->merge([
            'publisher_id' => current_publisher()->id,
            'slug' => Str::slug($request->name),
        ])->toArray());

        $addon->versions()->create([
            'version' => $request->version,
            'resource' => $request->resource,
        ]);

        return $this->success(__('Add-on created successfully'), 'website.publishers.dashboard.index', 'addons');
    }
}
