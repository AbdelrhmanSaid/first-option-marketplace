<x-page-header :title="__('Add-ons')" class="mb-3 mt-1">
    <a href="{{ route('website.publishers.dashboard.addons.create') }}" class="btn btn-primary">
        <i class="fa fa-plus me-2"></i>

        {{ __('Publish Add-on') }}
    </a>
</x-page-header>

<div class="row g-4">
    @forelse ($publisher->addons as $addon)
        <div class="col-md-6">
            <x-addon-card :addon="$addon" :link="route('website.publishers.dashboard.addons.edit', $addon->slug)" />
        </div>
    @empty
        <div class="col-12">
            <x-empty />
        </div>
    @endforelse
</div>
