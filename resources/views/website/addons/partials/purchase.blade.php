<div class="card mb-3">
    <div class="card-body">
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

        <div>
            @if ($addon->price)
                <button class="btn btn-primary w-100">
                    <i class="fas fa-shopping-cart me-2"></i>
                    {{ __('Purchase Now') }}
                </button>
            @else
                <button class="btn btn-primary w-100">
                    <i class="fas fa-download me-2"></i>
                    {{ __('Download Free') }}
                </button>
            @endif
        </div>
    </div>
</div>
