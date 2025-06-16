<x-layouts::website :title="__('Publisher Dashboard')">
    <div class="container">
        <div class="row">
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
