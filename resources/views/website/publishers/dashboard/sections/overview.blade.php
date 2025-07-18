@php
    use App\Enums\SubscriptionStatus;

    // Get all addon IDs for this publisher
    $addonIds = $publisher->addons->pluck('id');

    // Calculate subscription stats
    $activeSubscriptions = \App\Models\Subscription::whereIn('addon_id', $addonIds)
        ->where('status', SubscriptionStatus::Active)
        ->count();

    $cancelledSubscriptions = \App\Models\Subscription::whereIn('addon_id', $addonIds)
        ->where('status', SubscriptionStatus::Cancelled)
        ->count();

    $expiredSubscriptions = \App\Models\Subscription::whereIn('addon_id', $addonIds)
        ->where('status', SubscriptionStatus::Expired)
        ->count();

    // Calculate team members count
    $teamMembersCount = $publisher->members()->count();

    // Calculate average ratings for all addons
    $avgRating = \App\Models\AddonRate::whereIn('addon_id', $addonIds)
        ->where('is_approved', true)
        ->avg('rate');
    $avgRating = $avgRating ? number_format($avgRating, 1) : 0;
@endphp

<x-page-header :title="__('Overview')" class="mb-3 mt-1" />

<div class="row g-4">
    {{-- Active Subscriptions --}}
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">{{ __('Active Subscriptions') }}</div>
                    <div class="ms-auto lh-1">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-secondary" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-chart-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="h1 mb-3">{{ number_format($activeSubscriptions) }}</div>
                <div class="d-flex mb-2">
                    <div>{{ __('Currently active addon subscriptions') }}</div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-success" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cancelled Subscriptions --}}
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">{{ __('Cancelled Subscriptions') }}</div>
                    <div class="ms-auto lh-1">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-secondary" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-times-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="h1 mb-3">{{ number_format($cancelledSubscriptions) }}</div>
                <div class="d-flex mb-2">
                    <div>{{ __('Subscriptions cancelled by users') }}</div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-secondary" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Expired Subscriptions --}}
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">{{ __('Expired Subscriptions') }}</div>
                    <div class="ms-auto lh-1">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-secondary" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-clock"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="h1 mb-3">{{ number_format($expiredSubscriptions) }}</div>
                <div class="d-flex mb-2">
                    <div>{{ __('Subscriptions that have expired') }}</div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-danger" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Team Members Count --}}
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">{{ __('Team Members') }}</div>
                    <div class="ms-auto lh-1">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-secondary" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-users"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="h1 mb-3">{{ number_format($teamMembersCount) }}</div>
                <div class="d-flex mb-2">
                    <div>{{ __('Total publisher team members') }}</div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-blue" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    {{-- Average Ratings --}}
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">{{ __('Average Ratings') }}</div>
                    <div class="ms-auto lh-1">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-secondary" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-star"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-3 me-2">{{ $avgRating }}</div>
                    <div class="me-3">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($avgRating) ? 'fas' : 'far' }} fa-star text-warning"></i>
                        @endfor
                    </div>
                </div>
                <div class="d-flex mb-2">
                    <div>{{ __('Average rating across all approved addon reviews') }}</div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-warning" style="width: {{ ($avgRating / 5) * 100 }}%" role="progressbar" aria-valuenow="{{ ($avgRating / 5) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions Card --}}
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Quick Actions') }}</h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('website.publishers.dashboard.index', 'addons') }}" class="btn btn-outline-primary">
                        <i class="fas fa-puzzle-piece me-2"></i>
                        {{ __('Manage Add-ons') }}
                    </a>
                    <a href="{{ route('website.publishers.dashboard.index', 'feedbacks') }}" class="btn btn-outline-info">
                        <i class="fas fa-comments me-2"></i>
                        {{ __('Review Feedbacks') }}
                    </a>
                    <a href="{{ route('website.publishers.dashboard.index', 'members') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-users me-2"></i>
                        {{ __('Manage Team') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
