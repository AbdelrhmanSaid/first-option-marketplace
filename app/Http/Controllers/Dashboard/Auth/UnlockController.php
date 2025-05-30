<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UnlockController extends Controller
{
    /**
     * Display the dashboard lock screen.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (session()->has('dashboard_locked') === false) {
            return redirect()->route('dashboard.index');
        }

        return view('dashboard.auth.unlock', [
            'admin' => current_admin(),
        ]);
    }

    /**
     * Lock the dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (Hash::check($request->password, current_admin()->password)) {
            $request->session()->forget('dashboard_locked');

            return redirect()->intended(route('dashboard.index'));
        }

        return back()->withErrors(['password' => __('auth.password')]);
    }
}
