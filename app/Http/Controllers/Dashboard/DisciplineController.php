<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Discipline;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.disciplines.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.disciplines.create');
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

        Discipline::create($validated);

        return $this->created(__('Discipline'), 'dashboard.disciplines.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discipline $discipline)
    {
        return view('dashboard.disciplines.edit', [
            'discipline' => $discipline,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discipline $discipline)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $discipline->update($validated);

        return $this->updated(__('Discipline'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discipline $discipline)
    {
        $discipline->delete();

        return $this->deleted(__('Discipline'));
    }
}
