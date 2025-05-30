<x-layouts::dashboard inline>
    <div class="card">
        <div class="card-header d-flex flex-column align-items-start">
            <p class="card-title">
                @if ($memo->icon)
                    <i class="{{ $memo->icon }} me-2"></i>
                @endif

                {{ $memo->title }}
            </p>

            @if ($memo->date)
                <h5 class="card-subtitle text-muted">
                    {{ $memo->date->diffForHumans() }} - {{ $memo->date->translatedFormat('d F Y') }}
                </h5>
            @endif
        </div>

        <div class="card-body">
            {!! $memo->content !!}
        </div>
    </div>
</x-layouts::dashboard>
