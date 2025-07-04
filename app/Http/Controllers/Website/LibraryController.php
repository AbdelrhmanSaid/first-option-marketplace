<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\UserAddon;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /**
     * Display the user's addon library.
     */
    public function index(Request $request)
    {
        $user = current_user();

        $query = $user->userAddons()
            ->with(['addon.publisher', 'addon.category', 'addon.software'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->active();
            } elseif ($status === 'expired') {
                $query->expired();
            } elseif ($status === 'trial') {
                $query->trial();
            }
        }

        $userAddons = $query->paginate(12)->withQueryString();

        // Get counts for different statuses
        $counts = [
            'all' => $user->userAddons()->count(),
            'active' => $user->userAddons()->active()->count(),
            'expired' => $user->userAddons()->expired()->count(),
            'trial' => $user->userAddons()->trial()->count(),
        ];

        return view('website.library.index', [
            'userAddons' => $userAddons,
            'counts' => $counts,
            'currentFilters' => $request->all(),
        ]);
    }

    /**
     * Show details of a specific addon in the user's library.
     */
    public function show(UserAddon $userAddon)
    {
        // Ensure the addon belongs to the current user
        if ($userAddon->user_id !== current_user()->id) {
            abort(404);
        }

        $userAddon->load(['addon.publisher', 'addon.versions']);

        return view('website.library.show', [
            'userAddon' => $userAddon,
        ]);
    }
}
