<x-layouts::website :title="__('Addons')">
    <div class="container">
        <div class="page-header mb-3">
            <div class="page-pretitle">{{ __('Explore') }}</div>
            <h2 class="page-title">{{ __('New Addons') }}</h2>
        </div>

        <div class="row">
            @forelse ($addons as $addon)
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <x-addon-card :addon="$addon" :link="route('website.addons.show', $addon)" />
                </div>
            @empty
                <div class="col-12">
                    <x-empty :title="__('No addons found')" />
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $addons->links() }}
        </div>
    </div>
</x-layouts::website>