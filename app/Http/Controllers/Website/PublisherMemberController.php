<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Jobs\SendNewUserNotification;
use App\Models\PublisherMember;
use App\Models\User;
use Illuminate\Http\Request;

class PublisherMemberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|max:255',
        ]);

        $publisher = current_user()->publisher;
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            $password = \Illuminate\Support\Str::random(8);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($password),
            ]);

            SendNewUserNotification::dispatch($user, $password);
        }

        if ($publisher->members()->where('user_id', $user->id)->exists()) {
            return $this->error(__('Member already exists'));
        }

        $publisher->members()->create([
            'user_id' => $user->id,
            'role' => $request->role
        ]);

        return $this->success(__('Member added successfully'));
    }

    public function destroy(PublisherMember $publisherMember)
    {
        $publisherMember->delete();

        return $this->success(__('Member removed successfully'));
    }
}
