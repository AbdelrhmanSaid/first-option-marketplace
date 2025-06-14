<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.static-pages.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StaticPage $staticPage)
    {
        return view('dashboard.static-pages.edit', [
            'staticPage' => $staticPage,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaticPage $staticPage)
    {
        $validated = $request->validate([
            'title' => 'required|array',
            'title.*' => 'required|string|max:255',
            'content' => 'nullable|array',
            'content.*' => 'nullable|string',
        ]);

        $staticPage->update($validated);

        return $this->updated(__('Page'));
    }
}