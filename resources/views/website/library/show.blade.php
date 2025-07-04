<x-layouts::website :title="$userAddon->addon->name . ' - ' . __('Library')">
    <div class="container py-4">
        <div class="page-header mb-4">
            <ol class="breadcrumb" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="{{ route('website.index') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('website.library.index') }}">{{ __('My Library') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $userAddon->addon->name }}</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Addon Information -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $userAddon->addon->icon }}" alt="{{ $userAddon->addon->name }}"
                                class="avatar avatar-xl" style="object-fit: cover;">
                            <div class="flex-grow-1">
                                <h1 class="h3 mb-1">{{ $userAddon->addon->name }}</h1>
                                <p class="text-muted mb-0">
                                    {{ __('by') }}
                                    <a href="{{ route('website.publishers.show', $userAddon->addon->publisher) }}"
                                        class="text-decoration-none">
                                        {{ $userAddon->addon->publisher->name }}
                                    </a>
                                </p>
                            </div>
                            <div class="text-end">
                                @if ($userAddon->isActive())
                                    <span class="badge bg-success text-success-fg fs-6">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-danger text-danger-fg fs-6">{{ __('Expired') }}</span>
                                @endif
                            </div>
                        </div>

                        @if ($userAddon->addon->short_description)
                            <p class="text-muted">{{ $userAddon->addon->short_description }}</p>
                        @endif

                        @if ($userAddon->addon->instructions)
                            <div class="mt-4">
                                <h5>{{ __('Installation Instructions') }}</h5>
                                <div class="border-start ps-3">
                                    {!! $userAddon->addon->instructions !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Available Versions -->
                @if ($userAddon->addon->versions->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Available Downloads') }}</h3>
                        </div>
                        <div class="card-body">
                            @foreach ($userAddon->addon->versions as $version)
                                <div
                                    class="d-flex align-items-center justify-content-between py-2 @if (!$loop->last) border-bottom @endif">
                                    <div>
                                        <strong>{{ __('Version') }} {{ $version->version }}</strong>
                                        <div class="text-muted small">
                                            {{ __('Released') }} {{ $version->created_at->format('M j, Y') }}
                                        </div>
                                    </div>
                                    <div>
                                        @if ($userAddon->isActive())
                                            <a href="{{ $version->resource }}" class="btn btn-primary btn-icon"
                                                download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <span class="btn btn-secondary btn-icon disabled">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Purchase Details -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Purchase Details') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">{{ __('Purchase Date') }}</div>
                                <div class="datagrid-content">{{ $userAddon->created_at->format('M j, Y') }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">{{ __('Type') }}</div>
                                <div class="datagrid-content">
                                    @if ($userAddon->is_trial)
                                        <span class="badge bg-info text-info-fg">{{ __('Trial') }}</span>
                                    @elseif($userAddon->price_paid == 0)
                                        <span class="badge bg-success text-success-fg">{{ __('Free') }}</span>
                                    @else
                                        <span class="badge bg-primary text-primary-fg">{{ __('Paid') }}</span>
                                    @endif
                                </div>
                            </div>
                            @if ($userAddon->price_paid > 0)
                                <div class="datagrid-item">
                                    <div class="datagrid-title">{{ __('Amount Paid') }}</div>
                                    <div class="datagrid-content">${{ number_format($userAddon->price_paid, 2) }}</div>
                                </div>
                            @endif
                            @if ($userAddon->expires_at)
                                <div class="datagrid-item">
                                    <div class="datagrid-title">{{ __('Expires At') }}</div>
                                    <div class="datagrid-content">
                                        <span @class([
                                            'text-danger' => $userAddon->isExpired(),
                                            'text-warning' =>
                                                !$userAddon->isExpired() && $userAddon->expires_at->diffInDays() <= 3,
                                        ])>
                                            {{ $userAddon->expires_at->format('M j, Y') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            @if ($userAddon->payment_reference)
                                <div class="datagrid-item">
                                    <div class="datagrid-title">{{ __('Payment Reference') }}</div>
                                    <div class="datagrid-content">
                                        <code>{{ $userAddon->payment_reference }}</code>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Quick Actions') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-list gap-2">
                            <a href="{{ route('website.addons.show', $userAddon->addon) }}" class="btn">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('View Addon Details') }}
                            </a>
                            <a href="{{ route('website.publishers.show', $userAddon->addon->publisher) }}"
                                class="btn">
                                <i class="fas fa-user me-2"></i>
                                {{ __('View Publisher') }}
                            </a>
                            @if ($userAddon->addon->learn_more_url)
                                <a href="{{ $userAddon->addon->learn_more_url }}" target="_blank" class="btn">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    {{ __('Learn More') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::website>
