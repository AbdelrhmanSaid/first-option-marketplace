@push('styles')
    <style>
        .screenshot-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .screenshot-item {
            cursor: pointer;
            border: 1px solid #e0e0e0;
            overflow: hidden;
            width: 120px;
            height: 120px;
        }

        .sidebar-sticky {
            position: sticky;
            top: 1rem;
        }
    </style>
@endpush

@if ($addon->description)
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title mb-0">{{ __('Description') }}</h3>
        </div>
        <div class="card-body">
            {!! $addon->description !!}

            @if ($addon->screenshots && is_array($addon->screenshots) && count($addon->screenshots) > 0)
                <p class="mb-2 fw-bold">{{ __('Screenshots') }}</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($addon->screenshots as $screenshot)
                        <div class="screenshot-item rounded">
                            <a href="{{ $screenshot['url'] }}" data-fancybox="screenshots">
                                <img src="{{ $screenshot['url'] }}" class="img-fluid" alt="screenshot" />
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif
