<?php

namespace App\Http\Controllers\Website;

use App\Http\Requests\Website\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('website.profile.edit', [
            'user' => current_user(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->fill($request->only('first_name', 'last_name', 'email'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->filled('password')) {
            $user->password = $request->get('password');
        }

        $user->save();

        return $this->updated(__('Profile'));
    }
}
