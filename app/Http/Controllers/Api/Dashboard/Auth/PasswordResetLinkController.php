<?php

namespace App\Http\Controllers\Api\Dashboard\Auth;

use App\Http\Controllers\Api\Dashboard\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        return $this->respond(message: __(Password::RESET_LINK_SENT));
    }
}
