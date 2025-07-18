@php
    $approvedRates = $addon->rates->where('is_approved', true);
    $averageRate = $approvedRates->avg('rate');
    $averageRate = $averageRate ? number_format($averageRate, 1) : null;
    $userRate = null;

    if (current_user()) {
        $userRate = $addon->rates->firstWhere('user_id', current_user()->id);
    }
@endphp

<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0">{{ __('Ratings') }}</h3>
        @if ($averageRate)
            <div class="d-flex align-items-center gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="{{ $i <= round($averageRate) ? 'fas' : 'far' }} fa-star text-warning"></i>
                @endfor
                <span class="ms-1">({{ $averageRate }})</span>
            </div>
        @endif
    </div>

    <div class="card-body">
        {{-- Rating Form --}}
        @auth('users')
            @if (current_user()?->hasActiveSubscription($addon))
                <x-form :action="route($userRate ? 'website.rates.update' : 'website.rates.store', $addon)" :method="$userRate ? 'PUT' : 'POST'" class="my-4">
                    <div class="mb-3">
                        <x-rating name="rate" :value="old('rate', $userRate->rate ?? 0)" :title="__('Your Rating')" required />
                    </div>

                    <div class="mb-3">
                        <x-textarea name="comment" :title="__('Comment')" rows="3">{{ old('comment', $userRate->comment ?? '') }}</x-textarea>
                    </div>

                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-save me-1"></i>
                        {{ $userRate ? __('Update') : __('Submit') }}
                    </button>
                </x-form>

                <hr class="my-4" />
            @endif
        @endauth

        {{-- Ratings List --}}
        @forelse ($approvedRates as $rate)
            <div class="mb-4">
                <div class="d-flex align-items-start gap-2 mb-3">
                    <x-avatar :name="$rate->user->full_name" size="md" />
                    <div>
                        <div class="fw-bold">{{ $rate->user->full_name }}</div>
                        <span class="text-muted small">{{ $rate->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="ms-auto">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $rate->rate ? 'fas' : 'far' }} fa-star text-warning"></i>
                        @endfor
                    </div>
                </div>

                <p class="mb-0 text-muted">{{ $rate->comment ?? __('No comment yet.') }}</p>
            </div>
        @empty
            <x-empty class="border-0" :title="__('No ratings yet')" icon="fas fa-star" />
        @endforelse
    </div>
</div>
