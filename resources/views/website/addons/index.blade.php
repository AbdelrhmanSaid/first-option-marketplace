@push('styles')
    <style>
        .marketplace-container {
            min-height: 60vh;
        }

        .filters-sidebar {
            background: var(--tblr-body-bg);
            border: 1px solid var(--tblr-border-color);
            border-radius: 8px;
            padding: 20px;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .filter-section {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--tblr-border-color);
        }

        .filter-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .filter-section h6 {
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--tblr-body-color);
        }

        .filter-option {
            margin-bottom: 8px;
        }

        .filter-option label {
            font-weight: normal;
            margin-bottom: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sort-controls {
            background: var(--tblr-body-bg);
            border: 1px solid var(--tblr-border-color);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .filters-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 16px;
        }

        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }

        .filter-tag {
            background: var(--tblr-primary-lt);
            color: var(--tblr-primary);
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-tag .remove {
            cursor: pointer;
            font-weight: bold;
        }

        .results-info {
            color: var(--tblr-body-color);
            font-size: 14px;
            margin-bottom: 16px;
        }

        @media (max-width: 768px) {
            .filters-sidebar {
                position: static;
                margin-bottom: 20px;
            }
        }
    </style>
@endpush

<x-layouts::website :title="__('Addons')">
    <div class="container py-4">
        <div class="page-header mb-4">
            <div class="page-pretitle">{{ __('Explore') }}</div>
            <h2 class="page-title">{{ __('Add-ons Marketplace') }}</h2>
        </div>

        <div class="row marketplace-container">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="filters-sidebar">
                    <div class="filters-header d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">{{ __('Filters') }}</h5>
                        @if (!empty(array_filter($currentFilters)))
                            <a href="{{ route('website.addons.index') }}" class="btn btn-sm btn-outline-secondary">
                                {{ __('Clear All') }}
                            </a>
                        @endif
                    </div>

                    <form id="filtersForm" action="{{ route('website.addons.index') }}" method="GET">
                        <!-- Preserve search query -->
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <!-- App Type Filter -->
                        <div class="filter-section">
                            <h6>{{ __('App Type') }}</h6>
                            <div class="filter-option">
                                <label>
                                    <input type="checkbox" name="app_type[]" value="free"
                                        {{ in_array('free', (array) request('app_type', [])) ? 'checked' : '' }}
                                        onchange="document.getElementById('filtersForm').submit()">
                                    <i class="fas fa-gift text-success me-1"></i>
                                    {{ __('Free Apps') }}
                                </label>
                            </div>
                            <div class="filter-option">
                                <label>
                                    <input type="checkbox" name="app_type[]" value="trial"
                                        {{ in_array('trial', (array) request('app_type', [])) ? 'checked' : '' }}
                                        onchange="document.getElementById('filtersForm').submit()">
                                    <i class="fas fa-clock text-warning me-1"></i>
                                    {{ __('Trial Apps') }}
                                </label>
                            </div>
                            <div class="filter-option">
                                <label>
                                    <input type="checkbox" name="app_type[]" value="paid"
                                        {{ in_array('paid', (array) request('app_type', [])) ? 'checked' : '' }}
                                        onchange="document.getElementById('filtersForm').submit()">
                                    <i class="fas fa-dollar-sign text-primary me-1"></i>
                                    {{ __('Paid Apps') }}
                                </label>
                            </div>
                        </div>

                        <!-- OS Filter -->
                        <div class="filter-section">
                            <h6>{{ __('Operating System') }}</h6>
                            @foreach ($osOptions as $key => $label)
                                <div class="filter-option">
                                    <label>
                                        <input type="checkbox" name="os[]" value="{{ $key }}"
                                            {{ in_array($key, (array) request('os', [])) ? 'checked' : '' }}
                                            onchange="document.getElementById('filtersForm').submit()">
                                        <i class="fas fa-desktop text-info me-1"></i>
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Software Filter -->
                        <div class="filter-section">
                            <h6>{{ __('Software') }}</h6>
                            @foreach ($software as $sw)
                                <div class="filter-option">
                                    <label>
                                        <input type="checkbox" name="software[]" value="{{ $sw->id }}"
                                            {{ in_array($sw->id, (array) request('software', [])) ? 'checked' : '' }}
                                            onchange="document.getElementById('filtersForm').submit()">
                                        <i class="fas fa-cube text-secondary me-1"></i>
                                        {{ $sw->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Category Filter -->
                        <div class="filter-section">
                            <h6>{{ __('Category') }}</h6>
                            @foreach ($categories as $category)
                                <div class="filter-option">
                                    <label>
                                        <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                            {{ in_array($category->id, (array) request('category', [])) ? 'checked' : '' }}
                                            onchange="document.getElementById('filtersForm').submit()">
                                        <i class="fas fa-folder text-warning me-1"></i>
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Discipline Filter -->
                        <div class="filter-section">
                            <h6>{{ __('Discipline') }}</h6>
                            @foreach ($disciplines as $discipline)
                                <div class="filter-option">
                                    <label>
                                        <input type="checkbox" name="discipline[]" value="{{ $discipline->id }}"
                                            {{ in_array($discipline->id, (array) request('discipline', [])) ? 'checked' : '' }}
                                            onchange="document.getElementById('filtersForm').submit()">
                                        <i class="fas fa-graduation-cap text-success me-1"></i>
                                        {{ $discipline->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <!-- Search Bar -->
                <div class="search-bar mb-4">
                    <x-form :action="route('website.addons.index')" method="GET">
                        <!-- Preserve current filters -->
                        @foreach ($currentFilters as $key => $value)
                            @if ($key !== 'search' && $key !== 'page')
                                @if (is_array($value))
                                    @foreach ($value as $v)
                                        <input type="hidden" name="{{ $key }}[]"
                                            value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endif
                        @endforeach

                        <x-input class="form-control-lg" name="search" :placeholder="__('Search for add-ons...')" />
                    </x-form>
                </div>

                <!-- Active Filters -->
                @if (!empty(array_filter($currentFilters)))
                    <div class="active-filters">
                        @if (request('search'))
                            <span class="filter-tag">
                                {{ __('Search') }}: "{{ request('search') }}"
                                <span class="remove" onclick="removeFilter('search')">&times;</span>
                            </span>
                        @endif

                        @foreach ((array) request('app_type', []) as $type)
                            <span class="filter-tag">
                                {{ __('Type') }}: {{ ucfirst($type) }}
                                <span class="remove"
                                    onclick="removeArrayFilter('app_type', '{{ $type }}')">&times;</span>
                            </span>
                        @endforeach

                        @foreach ((array) request('os', []) as $os)
                            <span class="filter-tag">
                                {{ __('OS') }}: {{ $osOptions[$os] ?? $os }}
                                <span class="remove"
                                    onclick="removeArrayFilter('os', '{{ $os }}')">&times;</span>
                            </span>
                        @endforeach

                        @foreach ((array) request('software', []) as $swId)
                            @php $sw = $software->find($swId); @endphp
                            @if ($sw)
                                <span class="filter-tag">
                                    {{ __('Software') }}: {{ $sw->name }}
                                    <span class="remove"
                                        onclick="removeArrayFilter('software', '{{ $swId }}')">&times;</span>
                                </span>
                            @endif
                        @endforeach

                        @foreach ((array) request('category', []) as $catId)
                            @php $cat = $categories->find($catId); @endphp
                            @if ($cat)
                                <span class="filter-tag">
                                    {{ __('Category') }}: {{ $cat->name }}
                                    <span class="remove"
                                        onclick="removeArrayFilter('category', '{{ $catId }}')">&times;</span>
                                </span>
                            @endif
                        @endforeach

                        @foreach ((array) request('discipline', []) as $discId)
                            @php $disc = $disciplines->find($discId); @endphp
                            @if ($disc)
                                <span class="filter-tag">
                                    {{ __('Discipline') }}: {{ $disc->name }}
                                    <span class="remove"
                                        onclick="removeArrayFilter('discipline', '{{ $discId }}')">&times;</span>
                                </span>
                            @endif
                        @endforeach
                    </div>
                @endif

                <!-- Addons Grid -->
                <div class="row g-4" id="addonsContainer">
                    @forelse ($addons as $addon)
                        <div class="col-md-6">
                            <x-addon-card :addon="$addon" :link="route('website.addons.show', $addon)" />
                        </div>
                    @empty
                        <div class="col-12">
                            <x-empty />
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($addons->hasPages())
                    <div class="mt-4">
                        {{ $addons->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function removeFilter(filterName) {
                const url = new URL(window.location);
                url.searchParams.delete(filterName);
                url.searchParams.delete('page'); // Reset pagination
                window.location.href = url.toString();
            }

            function removeArrayFilter(filterName, value) {
                const url = new URL(window.location);
                const currentValues = url.searchParams.getAll(filterName + '[]');

                // Remove all instances of this filter
                url.searchParams.delete(filterName + '[]');

                // Add back all values except the one we want to remove
                currentValues.forEach(v => {
                    if (v !== value) {
                        url.searchParams.append(filterName + '[]', v);
                    }
                });

                url.searchParams.delete('page'); // Reset pagination
                window.location.href = url.toString();
            }

            function setView(viewType) {
                const url = new URL(window.location);
                url.searchParams.set('view', viewType);
                url.searchParams.delete('page'); // Reset pagination
                window.location.href = url.toString();
            }

            // Auto-submit form when Enter is pressed in search
            document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.target.form.submit();
                }
            });
        </script>
    @endpush
</x-layouts::website>
