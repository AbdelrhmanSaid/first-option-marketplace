<x-layouts::website :title="__('Publisher Dashboard')">
    <div class="container">
        <x-status />

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex gap-3">
                    <x-avatar :image="$publisher->logo" :name="$publisher->name" size="lg" />

                    <div>
                        <h1 class="h3 mb-1">
                            {{ $publisher->name }}
                            @if ($publisher->is_verified)
                                <i class="far fa-check-circle text-success ms-1"></i>
                            @endif
                        </h1>

                        <p class="text-muted mb-0">{{ $publisher->headline }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-3">
            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Dashboard') }}</h3>
                    </div>

                    <div class="list-group list-group-flush">
                        @foreach ($segments as $key => $value)
                            <a href="{{ route('website.publisher.dashboard', $key) }}" @class([
                                'list-group-item list-group-item-action',
                                'active' => $segment === $key,
                            ])>
                                {{ $value }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-9">
                @include("website.publisher.partials.$segment")
            </div>
        </div>
    </div>
</x-layouts::website>
