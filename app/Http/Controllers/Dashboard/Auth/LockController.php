<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LockController extends Controller
{
    /**
     * Put the dashboard in lock mode.
     */
    public function store(Request $request)
    {
        $request->session()->put('dashboard_locked', true);

        return redirect()->back();
    }
}
