<x-layouts::website :title="__('Publisher Dashboard')">
    <div class="container">
        <x-status />

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex gap-3">
                    <x-avatar :image="$publisher->logo" :name="$publisher->name" size="lg" />

                    <div>
                        <h1 class="h3 mb-1 d-flex align-items-center gap-2">
                            {{ $publisher->name }}
                            @if ($publisher->is_verified)
                                <x-icons.verified class="text-primary" />
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
                            @if (in_array(current_user()->member->role, $value['roles']))
                                <a href="{{ route('website.publishers.dashboard.index', $key) }}" @class([
                                    'list-group-item list-group-item-action',
                                    'active' => $segment === $key,
                                ])>
                                    <i class="{{ $value['icon'] }} me-2"></i>
                                    {{ $value['title'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-9">
                @include("website.publishers.dashboard.sections.$segment")
            </div>
        </div>
    </div>
</x-layouts::website>
