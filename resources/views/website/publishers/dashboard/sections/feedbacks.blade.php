@php
    // Get all rates for the current publisher addons
    $rates = \App\Models\AddonRate::with(['user', 'addon'])
        ->whereIn('addon_id', $publisher->addons->pluck('id'))
        ->latest()
        ->get();
@endphp

<x-page-header :title="__('Feedbacks')" class="mb-3 mt-1" />

<div class="row g-4">
    @forelse ($rates as $rate)
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        {{-- User Avatar & Info --}}
                        <x-avatar :name="$rate->user->full_name" size="md" />

                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong>{{ $rate->user->full_name }}</strong>
                                <span class="text-muted small">@ {{ $rate->created_at->diffForHumans() }}</span>
                            </div>

                            {{-- Stars --}}
                            <div class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $rate->rate ? 'fas' : 'far' }} fa-star text-warning"></i>
                                @endfor
                            </div>

                            {{-- Comment --}}
                            <p class="mb-0 text-muted">{{ $rate->comment ?? __('No comment.') }}</p>

                            {{-- Addon Name --}}
                            <p class="mb-0 mt-2">
                                <span class="badge bg-info text-info-fg"><i class="fas fa-puzzle-piece me-1"></i> {{ $rate->addon->name }}</span>
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="ms-auto text-end">
                            @if ($rate->is_approved === null)
                                <x-form :action="route('website.publishers.dashboard.feedbacks.approve', $rate)" method="PATCH" class="d-inline">
                                    <button class="btn btn-success btn-sm" type="submit">
                                        <i class="fas fa-check me-1"></i>{{ __('Approve') }}
                                    </button>
                                </x-form>
                                <x-form :action="route('website.publishers.dashboard.feedbacks.decline', $rate)" method="PATCH" class="d-inline ms-1">
                                    <button class="btn btn-danger btn-sm" type="submit">
                                        <i class="fas fa-times me-1"></i>{{ __('Decline') }}
                                    </button>
                                </x-form>
                            @else
                                <span class="badge {{ $rate->is_approved ? 'bg-success text-success-fg' : 'bg-danger text-danger-fg' }}">
                                    {{ $rate->is_approved ? __('Approved') : __('Declined') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <x-empty :title="__('No feedbacks yet')" icon="fas fa-comment" />
        </div>
    @endforelse
</div>
