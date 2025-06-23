<x-layouts::website :title="__('Addons')">
    <div class="container">
        <div class="page-header mb-3">
            <div class="page-pretitle">{{ __('Explore') }}</div>
            <h2 class="page-title">{{ __('New Addons') }}</h2>
        </div>

        <div class="row">
            @foreach ($addons as $addon)
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <x-addon-card :addon="$addon" :link="route('website.addons.show', $addon)" />
                </div>
            @endforeach
        </div>

        {{ $addons->links() }}
    </div>
</x-layouts::website>