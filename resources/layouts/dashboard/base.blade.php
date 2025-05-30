<x-layouts::scaffold :title="$title" :inline="$inline" {{ $attributes }}>
    @themer('dashboard-theme')

    @pushOnce('styles')
        <link rel="stylesheet" href="{{ hashed_asset('assets/css/dashboard.css') }}" />
    @endPushOnce

    @if (setting('page_loader_enabled'))
        <x-page-loader />
    @endif

    <main class="page">
        @if (! $inline)
            @include('layouts.dashboard.partials.sidebar.base', ['items' => $items])
            @include('layouts.dashboard.partials.navbar')
        @endif

        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-fluid">
                    <x-status />

                    {{ $slot }}

                    @if (! $inline)
                        @include('layouts.dashboard.partials.footer')
                    @endif
                </div>
            </div>
        </div>
    </main>
</x-layouts::scaffold>
