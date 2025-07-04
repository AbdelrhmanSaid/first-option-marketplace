<x-layouts::website :title="__('My Library')">
    <div class="container py-4">
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h2 mb-0">{{ __('My Library') }}</h1>
                    <p class="text-muted mb-0">{{ __('Manage your purchased addons') }}</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('website.addons.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        {{ __('Browse Add-ons') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Status filter tabs -->
        <div class="card mb-3">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs bg-transparent">
                    <li class="nav-item">
                        <a href="{{ route('website.library.index') }}" @class(['nav-link', 'active' => !request('status')])>
                            {{ __('All') }}
                            <span class="badge bg-secondary text-secondary-fg ms-2">{{ $counts['all'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('website.library.index', ['status' => 'active']) }}"
                            @class(['nav-link', 'active' => request('status') === 'active'])>
                            {{ __('Active') }}
                            <span class="badge bg-success text-success-fg ms-2">{{ $counts['active'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('website.library.index', ['status' => 'trial']) }}"
                            @class(['nav-link', 'active' => request('status') === 'trial'])>
                            {{ __('Trial') }}
                            <span class="badge bg-info text-info-fg ms-2">{{ $counts['trial'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('website.library.index', ['status' => 'expired']) }}"
                            @class(['nav-link', 'active' => request('status') === 'expired'])>
                            {{ __('Expired') }}
                            <span class="badge bg-warning text-warning-fg ms-2">{{ $counts['expired'] }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>{{ __('Addon') }}</th>
                            <th>{{ __('Publisher') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Expires At') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="w-1">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userAddons as $userAddon)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <x-avatar :image="$userAddon->addon->icon" :name="$userAddon->addon->name" size="md" style="min-width: 40px" />
                                        <div>
                                            <div class="fw-bold text-nowrap">{{ $userAddon->addon->name }}</div>
                                            <div class="text-muted small text-nowrap">
                                                {{ Str::limit($userAddon->addon->short_description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('website.publishers.show', $userAddon->addon->publisher) }}"
                                        class="text-decoration-none text-nowrap">
                                        {{ $userAddon->addon->publisher->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-outline bg-secondary text-secondary-fg">
                                        @if ($userAddon->is_trial)
                                            {{ __('Trial') }}
                                        @elseif($userAddon->isSubscription())
                                            {{ __('Subscription') }} - {{ ucfirst($userAddon->subscription_period) }}
                                        @elseif($userAddon->price_paid == 0)
                                            {{ __('Free') }}
                                        @else
                                            {{ __('Paid') }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @if ($userAddon->isSubscription() && $userAddon->next_billing_date)
                                        <span @class([
                                            'text-nowrap',
                                            'text-danger' => $userAddon->needsRenewal() || $userAddon->isInGracePeriod(),
                                            'text-warning' =>
                                                !$userAddon->needsRenewal() && !$userAddon->isInGracePeriod() && $userAddon->next_billing_date->diffInDays() <= 3,
                                        ])>
                                            {{ $userAddon->next_billing_date->format('M j, Y') }}
                                            @if($userAddon->needsRenewal())
                                                <small class="text-danger">({{ __('Renewal Due') }})</small>
                                            @elseif($userAddon->isInGracePeriod())
                                                <small class="text-danger">({{ __('Grace Period') }})</small>
                                            @endif
                                        </span>
                                    @elseif ($userAddon->expires_at)
                                        <span @class([
                                            'text-nowrap',
                                            'text-danger' => $userAddon->isExpired(),
                                            'text-warning' =>
                                                !$userAddon->isExpired() && $userAddon->expires_at->diffInDays() <= 3,
                                        ])>
                                            {{ $userAddon->expires_at->format('M j, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">{{ __('Never') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($userAddon->isActive())
                                        <span class="badge bg-success text-success-fg">{{ __('Active') }}</span>
                                    @elseif ($userAddon->isInGracePeriod())
                                        <span class="badge bg-warning text-warning-fg">{{ __('Grace Period') }}</span>
                                    @else
                                        <span class="badge bg-danger text-danger-fg">{{ __('Expired') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('website.addons.show', $userAddon->addon) }}"
                                            class="btn btn-outline-primary btn-icon">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($userAddon->isActive() || $userAddon->isInGracePeriod())
                                            <a href="{{ route('website.library.show', $userAddon) }}"
                                                class="btn btn-primary btn-icon">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif

                                        {{-- Subscription Management Actions --}}
                                        @if ($userAddon->isSubscription())
                                            @if ($userAddon->needsRenewal() || $userAddon->isInGracePeriod())
                                                <a href="{{ route('website.subscriptions.renewal', $userAddon) }}"
                                                    class="btn btn-warning btn-icon" title="{{ __('Renew Subscription') }}">
                                                    <i class="fas fa-sync"></i>
                                                </a>
                                            @endif

                                            @if ($userAddon->auto_renew && $userAddon->isActive())
                                                <form action="{{ route('website.subscriptions.cancel', $userAddon) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-icon"
                                                            title="{{ __('Cancel Auto-renewal') }}"
                                                            onclick="return confirm('{{ __('Are you sure you want to cancel auto-renewal?') }}')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @elseif (!$userAddon->auto_renew && $userAddon->isActive())
                                                <form action="{{ route('website.subscriptions.reactivate', $userAddon) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success btn-icon"
                                                            title="{{ __('Reactivate Auto-renewal') }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        {{-- Trial Conversion --}}
                                        @if ($userAddon->is_trial && $userAddon->addon->isSubscription())
                                            <a href="{{ route('website.subscriptions.trial-conversion', $userAddon) }}"
                                                class="btn btn-success btn-icon" title="{{ __('Convert Trial') }}">
                                                <i class="fas fa-crown"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty">
                                        <div class="empty-icon">
                                            <i class="fas fa-puzzle-piece fa-3x text-muted"></i>
                                        </div>
                                        <p class="empty-title">{{ __('No addons found') }}</p>
                                        <p class="empty-subtitle text-muted">
                                            @if (request('search') || request('status'))
                                                {{ __('Try adjusting your search or filter criteria.') }}
                                            @else
                                                {{ __('You haven\'t purchased any addons yet.') }}
                                            @endif
                                        </p>
                                        <div class="empty-action">
                                            <a href="{{ route('website.addons.index') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>
                                                {{ __('Browse Add-ons') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($userAddons->hasPages())
                    <div class="card-footer">
                        {{ $userAddons->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::website>
