<?php

namespace App\Http\Controllers\Website;

use App\Enums\PublisherMemberRole;
use App\Models\Publisher;
use App\Traits\CanUploadFile;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    use CanUploadFile;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('website.publisher.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('website.publisher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'headline' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:publishers,email',
            'website' => 'nullable|url|max:255',
            'header_image' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('header_image')) {
            $validated['header_image'] = $this->uploadFile($request->file('header_image'), 'publishers');
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->uploadFile($request->file('logo'), 'publishers');
        }

        // Create the publisher account
        $publisher = Publisher::create($validated);

        // Attach the current user as the owner of the publisher
        $publisher->members()->create([
            'user_id' => current_user()->id,
            'role' => PublisherMemberRole::Owner->value,
        ]);

        return $this->success(__('Publisher account created successfully.'), 'website.publisher.dashboard');
    }
}
