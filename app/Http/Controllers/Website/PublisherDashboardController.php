<?php

namespace App\Http\Controllers\Website;

use App\Enums\PublisherMemberRole;

class PublisherDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $segment = 'overview')
    {
        $segments = $this->segments();
        $segment = array_key_exists($segment, $segments) ? $segment : array_key_first($segments);

        // Get the publisher account for the current user
        $publisher = current_publisher();

        return view('website.publishers.dashboard.index', [
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
            'addons' => [
                'title' => __('Add-ons'),
                'icon' => 'fas fa-puzzle-piece',
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
            'members' => [
                'title' => __('Members'),
                'icon' => 'fas fa-users',
                'roles' => [PublisherMemberRole::Owner, PublisherMemberRole::Admin],
            ],
            'settings' => [
                'title' => __('Settings'),
                'icon' => 'fas fa-cog',
                'roles' => [PublisherMemberRole::Owner, PublisherMemberRole::Admin],
            ],
        ];
    }
}
