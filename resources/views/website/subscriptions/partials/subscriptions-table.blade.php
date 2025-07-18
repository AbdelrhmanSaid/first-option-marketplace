<table class="table card-table table-vcenter">
    <thead>
        <tr>
            <th>{{ __('Add-on') }}</th>
            <th>{{ __('Frequency') }}</th>
            <th>{{ __('Price') }}</th>
            <th>{{ __('Ends At') }}</th>
            <th>{{ __('Status') }}</th>
            <th class="w-1"></th>
        </tr>
    </thead>

    <tbody>
        @forelse($subscriptions as $subscription)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <x-avatar :image="$subscription->addon->icon" class="me-2" />

                        <div class="text-truncate">
                            <div class="fw-semibold">{{ $subscription->addon->name }}</div>
                            <small class="text-muted">{{ $subscription->addon->publisher->name }}</small>
                        </div>
                    </div>
                </td>

                <td class="text-nowrap">
                    {{ $subscription->frequency }}
                    {{ $subscription->frequency > 1 ? __('months') : __('month') }}
                </td>

                <td class="text-nowrap">{{ number_format($subscription->price, 2) }} {{ __('EGP') }}</td>
                <td class="text-nowrap">{{ $subscription->end_date->format('d M Y') }}</td>

                <td>
                    <span class="badge bg-{{ $subscription->status->color() }} text-white">
                        {{ $subscription->status->label() }}
                    </span>
                </td>

                <td class="text-end">
                    <div class="btn-list flex-nowrap">
                        @if ($subscription->status === \App\Enums\SubscriptionStatus::Active)
                            <a onclick="cancelSubscription({{ $subscription->id }})" class="btn btn-icon">
                                <i class="fa-solid fa-ban"></i>
                            </a>

                            <a href="{{ route('website.subscriptions.download', $subscription) }}"
                                class="btn btn-icon">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        @elseif ($subscription->status === \App\Enums\SubscriptionStatus::Cancelled)
                            <a onclick="renewSubscription({{ $subscription->id }})" class="btn btn-icon">
                                <i class="fa-solid fa-rotate-right"></i>
                            </a>
                        @endif

                        <a href="{{ route('website.addons.show', $subscription->addon) }}" class="btn btn-icon">
                            <i class="fa-solid fa-store"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <i class="fa-solid fa-circle-info fa-2x mb-3"></i>
                    <p class="m-0">{{ __('You don\'t have any subscriptions yet.') }}</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@push('scripts')
    <script>
        function cancelSubscription(id) {
            const endpoint = '{{ route('website.subscriptions.cancel', ':id') }}';

            warnBeforeAction(() => formRequest(endpoint.replace(':id', id)));
        }

        function renewSubscription(id) {
            const endpoint = '{{ route('website.subscriptions.renew', ':id') }}';

            warnBeforeAction(() => formRequest(endpoint.replace(':id', id)));
        }
    </script>
@endpush
