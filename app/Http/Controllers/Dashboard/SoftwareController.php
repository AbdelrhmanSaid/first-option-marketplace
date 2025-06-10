<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Software;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SoftwareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.software.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.software.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Set slug based on name
        $validated['slug'] = Str::slug($validated['name']);

        Software::create($validated);

        return $this->created(__('Software'), 'dashboard.software.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Software $software)
    {
        return view('dashboard.software.edit', [
            'software' => $software,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Software $software)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Set slug based on name
        $validated['slug'] = Str::slug($validated['name']);

        $software->update($validated);

        return $this->updated(__('Software'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Software $software)
    {
        $software->delete();

        return $this->deleted(__('Software'));
    }
}
