<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">{{ __('Details') }}</h4>
    </div>
    <div class="card-body">
        <div class="row g-2">
            @if ($addon->os)
                <div class="col-12">
                    <small class="text-muted">{{ __('Operating System') }}</small>
                    <div class="fw-bold">{{ $addon->os->label() }}</div>
                </div>
            @endif

            @if ($addon->versions->isNotEmpty())
                <div class="col-12">
                    <small class="text-muted">{{ __('Latest Version') }}</small>
                    <div class="fw-bold">
                        {{ optional($addon->versions->sortByDesc('id')->first())->version }}
                    </div>
                </div>
            @endif

            @if ($addon->created_at)
                <div class="col-12">
                    <small class="text-muted">{{ __('Release Date') }}</small>
                    <div class="fw-bold">{{ $addon->created_at->format('M d, Y') }}</div>
                </div>
            @endif

            @if ($addon->updated_at)
                <div class="col-12">
                    <small class="text-muted">{{ __('Last Updated') }}</small>
                    <div class="fw-bold">{{ $addon->updated_at->format('M d, Y') }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
