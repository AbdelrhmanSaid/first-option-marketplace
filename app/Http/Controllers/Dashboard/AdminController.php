<?php

namespace App\Http\Controllers\Dashboard;

use App\Jobs\SendNewAdminNotification;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.admins.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name');

        return view('dashboard.admins.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'role' => 'nullable|exists:roles,name',
        ]);

        $password = Str::random(8);

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $password,
            'active' => $request->has('active'),
        ]);

        if ($validated['role']) {
            $admin->assignRole($validated['role']);
        }

        SendNewAdminNotification::dispatch($admin, $password);

        return $this->created(__('Admin'), 'dashboard.admins.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        $roles = Role::pluck('name', 'name');

        return view('dashboard.admins.edit', [
            'admin' => $admin,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'role' => 'nullable|exists:roles,name',
        ]);

        cache()->flush(); // Clear cache to refresh permissions

        // Sync roles for the admin
        $admin->syncRoles($validated['role']);

        $admin->update([
            'active' => $request->has('active'),
        ]);

        return $this->updated(__('Admin'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        if ($admin->id === current_admin()->id) {
            return back()->with('error', __('You cannot delete yourself.'));
        }

        $admin->delete();

        return $this->deleted(__('Admin'));
    }
}
