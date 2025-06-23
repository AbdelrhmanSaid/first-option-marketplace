<x-layouts::website :title="__('Publishers')">
    @push('styles')
        <style>
            .publisher-card {
                transition: transform 0.3s ease-in-out;
            }

            .publisher-card:hover {
                transform: translateY(-5px);
            }
        </style>
    @endpush

    <div class="container py-4">
        <h1 class="h2 mb-4">{{ __('Publishers') }}</h1>

        <div class="row">
            @foreach ($publishers as $publisher)
                <div class="col-12 col-md-6 col-lg-4 mb-3 publisher-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <x-avatar :image="$publisher->logo" :name="$publisher->name" size="md" />

                                <div class="flex-grow-1 min-w-0">
                                    <h5 class="card-title mb-0">
                                        <a href="{{ route('website.publishers.show', $publisher) }}" class="text-decoration-none stretched-link">
                                            {{ $publisher->name }}
                                        </a>
                                    </h5>

                                    @if ($publisher->headline)
                                        <p class="card-text text-muted">{{ $publisher->headline }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $publishers->links() }}
    </div>
</x-layouts::website>