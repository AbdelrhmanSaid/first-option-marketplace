<?php

namespace App\Http\Controllers\Api\Dashboard\Auth;

use App\Http\Controllers\Api\Dashboard\Controller;
use App\Jobs\SendNewAdminNotification;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'role' => 'required|exists:roles,name',
        ]);

        $password = Str::random(8);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);

        SendNewAdminNotification::dispatch($admin, $password);

        return $this->respond(message: 'Admin created successfully');
    }
}
