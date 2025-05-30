<?php

namespace App\Http\Controllers\Api\Website\Auth;

use App\Http\Controllers\Api\Website\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->fail(message: 'Email already verified.', code: 403, payload: [
                'already_verified' => true,
            ]);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->respond(message: 'Email successfully verified.', payload: [
            'already_verified' => false,
        ]);
    }
}
