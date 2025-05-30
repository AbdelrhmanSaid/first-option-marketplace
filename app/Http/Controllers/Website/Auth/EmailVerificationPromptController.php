<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Website\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('website.index'))
            : view('website.auth.verify-email');
    }
}
