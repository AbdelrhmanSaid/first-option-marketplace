<x-layouts::website.compact :title="$subscription->addon->name">
    <div class="card py-5">
        <div class="card-body">
            <div class="d-flex flex-column align-items-center justify-content-center text-center gap-5">
                <div>
                    <i class="fas fa-check-circle fa-6x text-primary"></i>
                </div>

                <div>
                    <h2 class="display-6 mb-3 fw-semibold">
                        {{ __('Subscription Successful') }}
                    </h2>

                    <p class="text-secondary fs-3">
                        {{ __('You have been successfully subscribed to the addon :addon, ending on :date.', [
                            'addon' => $subscription->addon->name,
                            'date' => $subscription->end_date->format('d M Y'),
                        ]) }}
                    </p>
                </div>

                <div class="btn-list">
                    <a href="{{ route('website.subscriptions.index') }}" class="btn btn-primary btn-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts::website.compact>
