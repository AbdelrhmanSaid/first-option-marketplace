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
            @if (current_user()->hasActiveSubscription($addon))
                <a class="btn btn-primary w-100"
                    href="{{ route('website.subscriptions.download', current_user()->subscriptions()->where('addon_id', $addon->id)->first()) }}">
                    <i class="fas fa-download me-2"></i>
                    {{ __('Download') }}
                </a>

                <p class="text-muted small m-0 mt-2">
                    {{ __('You have an active subscription for this addon.') }}
                </p>
            @else
                <a class="btn btn-primary w-100" href="{{ route('website.subscriptions.create', $addon) }}">
                    @if ($addon->price && $addon->trial_period)
                        <i class="fas fa-play me-2"></i>
                        {{ __('Start Trial') }}
                    @elseif ($addon->price)
                        <i class="fas fa-shopping-cart me-2"></i>
                        {{ __('Purchase Now') }}
                    @else
                        <i class="fas fa-download me-2"></i>
                        {{ __('Download Free') }}
                    @endif
                </a>
            @endif
        </div>
    </div>
</div>
