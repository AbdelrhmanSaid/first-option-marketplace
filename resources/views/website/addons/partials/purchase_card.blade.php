<div class="card mb-3">
    <div class="card-body text-center">
        <div class="mb-3">
            @if ($addon->price)
                <div class="h2 text-success mb-1">
                    {{ number_format($addon->price, 0) }} {{ __('EGP') }}
                    <small class="h6 text-muted">/{{ __('mo') }}</small>
                </div>

                @if ($addon->trial_period)
                    <div class="text-success small">
                        {{ $addon->trial_period }} {{ __('days trial') }}
                    </div>
                @endif
            @else
                <div class="h2 text-success mb-1">{{ __('Free') }}</div>
            @endif
        </div>

        @if ($addon->price)
            <button class="btn btn-primary w-100 mb-2">
                <i class="fas fa-shopping-cart me-2"></i>
                {{ __('Purchase Now') }}
            </button>
            @if ($addon->trial_period)
                <button class="btn w-100 mb-3">
                    <i class="fas fa-play me-2"></i>
                    {{ __('Start Free Trial') }}
                </button>
            @endif
        @else
            <button class="btn btn-success w-100 mb-3">
                <i class="fas fa-download me-2"></i>
                {{ __('Download Free') }}
            </button>
        @endif
    </div>
</div>
