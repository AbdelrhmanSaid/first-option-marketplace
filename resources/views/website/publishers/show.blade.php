<x-layouts::website :title="$publisher->name">
    <div class="container py-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                    <x-avatar :image="$publisher->logo" :name="$publisher->name" size="xl" />

                    <div class="flex-grow-1">
                        <h1 class="h2 mb-0 d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                            {{ $publisher->name }}
                            @if ($publisher->is_verified)
                                <x-icons.verified class="text-primary" />
                            @endif
                        </h1>

                        @if ($publisher->headline)
                            <p class="text-muted mb-0">{{ $publisher->headline }}</p>
                        @endif
                    </div>

                    <div class="btn-list justify-content-end">
                        @if ($publisher->website)
                            <a href="{{ $publisher->website }}" target="_blank" class="btn">
                                <i class="fas fa-globe me-1"></i>
                                {{ __('Visit Website') }}
                            </a>
                        @endif

                        <a href="mailto:{{ $publisher->email }}" class="btn">
                            <i class="fas fa-envelope me-1"></i>
                            {{ __('Contact') }}
                        </a>
                    </div>
                </div>

                <hr class="my-4" />

                <div class="datagrid">
                    <div class="datagrid-item">
                        <div class="datagrid-title">{{ __('Contact Email') }}</div>
                        <div class="datagrid-content">
                            <a href="mailto:{{ $publisher->email }}" class="text-decoration-none">
                                {{ $publisher->email }}
                            </a>
                        </div>
                    </div>

                    @if ($publisher->website)
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Website') }}</div>
                            <div class="datagrid-content">
                                <a href="{{ $publisher->website }}" target="_blank" class="text-decoration-none">
                                    {{ $publisher->website }}
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="datagrid-item">
                        <div class="datagrid-title">{{ __('Member Since') }}</div>
                        <div class="datagrid-content">{{ $publisher->created_at->format('F Y') }}</div>
                    </div>

                    <div class="datagrid-item">
                        <div class="datagrid-title">{{ __('Members') }}</div>
                        <div class="datagrid-content">
                            {{ $publisher->members->count() }} {{ __('member(s)') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-header">
            <div class="page-pretitle">{{ __('Addons by') }} {{ $publisher->name }}</div>
        </div>

        <div class="row">
            @foreach ($publisher->addons as $addon)
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <x-addon-card :addon="$addon" :show-publisher="false" />
                </div>
            @endforeach
        </div>
    </div>
</x-layouts::website>
