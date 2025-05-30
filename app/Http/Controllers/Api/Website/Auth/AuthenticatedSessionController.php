<?php

namespace App\Http\Controllers\Api\Website\Auth;

use App\Http\Controllers\Api\Website\Controller;
use App\Http\Requests\Api\Website\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $user = $request->authenticate();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->respond([
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->respond(message: 'Logged out successfully.');
    }
}
