<x-layouts::website :title="$addon->name">
    <div class="container py-4">
        <x-status />

        <div class="row">
            <div class="col-lg-9">
                @include('website.addons.partials.main')
                @include('website.addons.partials.description')
                @include('website.addons.partials.links')
                @include('website.addons.partials.rates')
            </div>

            <div class="col-lg-3">
                <div class="sidebar-sticky">
                    @include('website.addons.partials.purchase')
                    @include('website.addons.partials.details')
                </div>
            </div>
        </div>
    </div>
</x-layouts::website>
