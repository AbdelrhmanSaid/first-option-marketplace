<?php

namespace App\Http\Controllers\Website;

use App\Enums\PublisherMemberRole;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $segment = 'overview')
    {
        $segments = $this->segments();
        $segment = array_key_exists($segment, $segments) ? $segment : array_key_first($segments);

        // Get the publisher account for the current user
        $publisher = current_user()->publisher;

        return view('website.publisher.index', [
            'segments' => $segments,
            'segment' => $segment,
            'publisher' => $publisher,
        ]);
    }

    /**
     * Get the segments for the publisher dashboard.
     */
    protected function segments(): array
    {
        return [
            'overview' => [
                'title' => __('Overview'),
                'icon' => 'fas fa-home',
                'roles' => [PublisherMemberRole::Owner, PublisherMemberRole::Admin, PublisherMemberRole::Member],
            ],
            'members' => [
                'title' => __('Members'),
                'icon' => 'fas fa-users',
                'roles' => [PublisherMemberRole::Owner, PublisherMemberRole::Admin],
            ],
            'add-ons' => [
                'title' => __('Add-ons'),
                'icon' => 'fas fa-plus',
                'roles' => [PublisherMemberRole::Owner, PublisherMemberRole::Admin, PublisherMemberRole::Member],
            ],
            'feedbacks' => [
                'title' => __('Feedbacks'),
                'icon' => 'fas fa-comments',
                'roles' => [PublisherMemberRole::Owner, PublisherMemberRole::Admin, PublisherMemberRole::Member],
            ],
            'payments' => [
                'title' => __('Payments'),
                'icon' => 'fas fa-money-bill',
                'roles' => [PublisherMemberRole::Owner, PublisherMemberRole::Admin],
            ],
            'settings' => [
                'title' => __('Settings'),
                'icon' => 'fas fa-cog',
                'roles' => [PublisherMemberRole::Owner, PublisherMemberRole::Admin],
            ],
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('website.publisher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'headline' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:publishers,email',
            'website' => 'nullable|url|max:255',
        ]);

        // Create the publisher account
        $publisher = Publisher::create($validated);

        // Attach the current user as the owner of the publisher
        $publisher->members()->create([
            'user_id' => current_user()->id,
            'role' => PublisherMemberRole::Owner->value,
        ]);

        return $this->success(__('Publisher account created successfully.'), 'website.publisher.dashboard');
    }
}
