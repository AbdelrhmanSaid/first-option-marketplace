<x-layouts::website :title="$addon->name">
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                @include('website.addons.partials.main_card')
                @include('website.addons.partials.description_card')
                @include('website.addons.partials.links_card')
            </div>

            <div class="col-lg-4">
                <div class="sidebar-sticky">
                    @include('website.addons.partials.purchase_card')
                    @include('website.addons.partials.details_card')
                </div>
            </div>
        </div>
    </div>
</x-layouts::website>
