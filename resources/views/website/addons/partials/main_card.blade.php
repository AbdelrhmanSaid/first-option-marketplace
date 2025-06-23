<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4">
            <x-avatar :image="$addon->icon" :name="$addon->name" size="xl" />

            <div class="flex-grow-1">
                <h1 class="h2 mb-0 d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                    {{ $addon->name }}
                </h1>

                @if ($addon->short_description)
                    <p class="text-muted text-md-start text-center mb-0">{{ $addon->short_description }}</p>
                @endif
            </div>
        </div>

        <hr class="my-4" />

        <div class="datagrid">
            @if ($addon->publisher)
                <div class="datagrid-item">
                    <div class="datagrid-title">{{ __('Publisher') }}</div>
                    <div class="datagrid-content">
                        <a href="{{ route('website.publishers.show', $addon->publisher) }}"
                            class="text-decoration-none">
                            {{ $addon->publisher->name }}
                        </a>
                        @if ($addon->publisher->is_verified)
                            <x-icons.verified class="text-primary" style="width: 14px; height: 14px;" />
                        @endif
                    </div>
                </div>
            @endif

            @if ($addon->software)
                <div class="datagrid-item">
                    <div class="datagrid-title">{{ __('Software') }}</div>
                    <div class="datagrid-content">{{ $addon->software->name }}</div>
                </div>
            @endif

            @if ($addon->category)
                <div class="datagrid-item">
                    <div class="datagrid-title">{{ __('Category') }}</div>
                    <div class="datagrid-content">{{ $addon->category->name }}</div>
                </div>
            @endif

            @if ($addon->discipline)
                <div class="datagrid-item">
                    <div class="datagrid-title">{{ __('Discipline') }}</div>
                    <div class="datagrid-content">{{ $addon->discipline->name }}</div>
                </div>
            @endif

            @if ($addon->tags && is_array($addon->tags) && count($addon->tags) > 0)
                <div class="datagrid-item">
                    <div class="datagrid-title">{{ __('Tags') }}</div>
                    <div class="datagrid-content">
                        <div class="tags-list">
                            @foreach ($addon->tags as $tag)
                                <span class="tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
