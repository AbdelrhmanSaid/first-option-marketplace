<x-layouts::website>
    @include('website.home.partials.hero')

    @if ($featuredAddons->count() > 0)
        @include('website.home.partials.featured-addons')
    @endif

    @include('website.home.partials.cta')
</x-layouts::website>
