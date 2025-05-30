<?php

namespace App\Http\Controllers\Api\Website\Auth;

use App\Http\Controllers\Api\Website\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->fail('Email already verified.', 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->respond(message: 'Email verification link sent!');
    }
}
