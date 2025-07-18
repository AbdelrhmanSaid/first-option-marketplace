@php
    use App\Enums\SubscriptionStatus;
    use App\Models\Subscription;

    $baseQuery = Subscription::whereIn('addon_id', $publisher->addons->pluck('id'));
    $paginatedQuery = $baseQuery->clone()->paginate(10);

    $stats = [
        [
            'title' => __('Active Subscriptions'),
            'value' => $baseQuery->clone()->where('status', SubscriptionStatus::Active)->count(),
            'icon' => 'fas fa-chart-line',
            'color' => 'success',
        ],
        [
            'title' => __('Cancelled Subscriptions'),
            'value' => $baseQuery->clone()->where('status', SubscriptionStatus::Cancelled)->count(),
            'icon' => 'fas fa-times-circle',
            'color' => 'secondary',
        ],
        [
            'title' => __('Expired Subscriptions'),
            'value' => $baseQuery->clone()->where('status', SubscriptionStatus::Expired)->count(),
            'icon' => 'fas fa-clock',
            'color' => 'danger',
        ],
        [
            'title' => __('Team Members'),
            'value' => $publisher->members()->count(),
            'icon' => 'fas fa-users',
            'color' => 'blue',
        ],
    ];
@endphp

@push('styles')
    <style>
        .pagination-wrapper p {
            margin: 0;
        }
    </style>
@endpush

<x-page-header :title="__('Overview')" class="mb-3 mt-1" />

<div class="row g-2">
    @foreach ($stats as $stat)
        <div class="col-12 col-md-6">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-{{ $stat['color'] }}-lt avatar">
                                <i class="fas {{ $stat['icon'] }}"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="fw-bold">{{ number_format($stat['value']) }}</div>
                            <div class="text-muted">{{ $stat['title'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title">{{ __('Subscriptions') }}</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ __('Addon') }}</th>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Created At') }}</th>
                    <th>{{ __('Expires At') }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($paginatedQuery as $subscription)
                    <tr>
                        <td>{{ $subscription->addon->name }}</td>
                        <td>{{ $subscription->user->full_name }}</td>
                        <td>{{ $subscription->status->label() }}</td>
                        <td>{{ $subscription->start_date->format('d/m/Y') }}</td>
                        <td>{{ $subscription->end_date->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">{{ __('No subscriptions found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer pagination-wrapper">
        {{ $paginatedQuery->links() }}
    </div>
</div>
