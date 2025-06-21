<?php

namespace App\Http\Controllers\Website;

use App\Jobs\SendNewMemberNotification;
use App\Jobs\SendNewUserNotification;
use App\Models\User;
use Illuminate\Http\Request;

class AddPublisherMemberController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|max:255',
        ]);

        $publisher = current_publisher();
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
            'role' => $request->role,
        ]);

        SendNewMemberNotification::dispatch($user, $publisher);

        return $this->success(__('Member added successfully'));
    }
}
