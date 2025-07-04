<div class="card mb-3">
    <div class="card-body">
        <div class="mb-3">
            @if ($addon->isFree())
                <div class="h2 text-success mb-1">{{ __('Free') }}</div>
            @elseif ($addon->isSubscription())
                {{-- Subscription Pricing --}}
                <div class="mb-3">
                    <h5 class="mb-2">{{ __('Subscription Plans') }}</h5>
                    @foreach($addon->getAvailableSubscriptionPeriods() as $period)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <strong>{{ ucfirst($period) }}</strong>
                                @if($period === $addon->getBestValuePeriod())
                                    <span class="badge bg-success text-success-fg ms-2">{{ __('Best Value') }}</span>
                                @endif
                            </div>
                            <div class="text-success fw-bold">
                                {{ $addon->getFormattedSubscriptionPrice($period) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($addon->trial_period)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __(':days days free trial available', ['days' => $addon->trial_period]) }}
                    </div>
                @endif
            @elseif ($addon->price)
                <div class="h2 text-success mb-1">
                    ${{ number_format($addon->price, 2) }}
                </div>

                @if ($addon->trial_period)
                    <div class="text-success small">
                        {{ $addon->trial_period }} {{ __('days trial') }}
                    </div>
                @endif
            @endif
        </div>

        <div>
            @auth('users')
                @if(current_user()->ownsAddon($addon))
                    <x-alert :title="__('You own this addon')" icon="fas fa-check-circle" />
                    <a href="{{ route('website.library.index') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-book me-2"></i>
                        {{ __('View in Library') }}
                    </a>
                @else
                    <!-- Purchase buttons based on addon type -->
                    @if ($addon->isFree())
                        <x-form :action="route('website.addons.purchase', $addon)" method="POST">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-download me-2"></i>
                                {{ __('Add to Library') }}
                            </button>
                        </x-form>
                    @elseif ($addon->hasTrial())
                        <x-form :action="route('website.addons.purchase', $addon)" method="POST">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-play me-2"></i>
                                {{ __('Start Trial') }}
                            </button>
                            <div class="mt-2 text-center">
                                <small class="text-muted">
                                    {{ __(':days days free trial', ['days' => $addon->trial_period]) }}
                                    @if($addon->isSubscription())
                                        <br>{{ __('Convert to subscription after trial') }}
                                    @elseif($addon->isPaid())
                                        <br>{{ __('then :price', ['price' => $addon->formatted_price]) }}
                                    @endif
                                </small>
                            </div>
                        </x-form>
                    @elseif ($addon->isSubscription())
                        {{-- Subscription Purchase Form --}}
                        <x-form :action="route('website.addons.purchase', $addon)" method="POST">
                            <div class="mb-3">
                                <label class="form-label">{{ __('Choose Subscription Period') }}</label>
                                <select name="period" class="form-select" required>
                                    <option value="">{{ __('Select a plan...') }}</option>
                                    @foreach($addon->getAvailableSubscriptionPeriods() as $period)
                                        <option value="{{ $period }}"
                                                @if($period === $addon->getBestValuePeriod()) selected @endif>
                                            {{ ucfirst($period) }} - {{ $addon->getFormattedSubscriptionPrice($period) }}
                                            @if($period === $addon->getBestValuePeriod())
                                                ({{ __('Best Value') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-credit-card me-2"></i>
                                {{ __('Subscribe Now') }}
                            </button>
                        </x-form>
                    @elseif ($addon->isPaid())
                        <x-form :action="route('website.addons.purchase', $addon)" method="POST">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-shopping-cart me-2"></i>
                                {{ __('Purchase Now') }}
                            </button>
                        </x-form>
                    @endif
                @endif
            @else
                <!-- Not authenticated - show login prompt -->
                <a href="{{ route('website.login') }}" class="btn btn-primary w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    {{ __('Login to Purchase') }}
                </a>
                <div class="mt-2 text-center">
                    <small class="text-muted">
                        {{ __('Don\'t have an account?') }}
                        <a href="{{ route('website.register') }}">{{ __('Sign up') }}</a>
                    </small>
                </div>
            @endauth
        </div>
    </div>
</div>
