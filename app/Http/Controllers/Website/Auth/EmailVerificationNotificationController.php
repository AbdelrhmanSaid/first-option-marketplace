<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Website\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('website.index'));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', __('A new verification link has been sent to the email address you provided during registration.'));
    }
}
