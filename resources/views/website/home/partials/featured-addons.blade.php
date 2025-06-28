<section class="py-5 bg-primary-lt">
    <div class="container">
        <h2 class="display-5 mb-3">{{ __('Popular Add-ons') }}</h2>
        <p class="lead text-muted mb-4">
            {{ __('Explore our most popular add-ons trusted by thousands of professionals worldwide') }}
        </p>

        <div class="row g-4 mb-4">
            @foreach ($featuredAddons as $addon)
                <div class="col-lg-4 col-md-6">
                    <x-addon-card :addon="$addon" :link="route('website.addons.show', $addon)" />
                </div>
            @endforeach
        </div>

        <div class="text-end">
            <a href="{{ route('website.addons.index') }}" class="btn">
                {{ __('Explore All Add-ons') }}

                @if (\App\Models\Language::current()->is_rtl)
                    <i class="fas fa-arrow-left ms-2"></i>
                @else
                    <i class="fas fa-arrow-right ms-2"></i>
                @endif
            </a>
        </div>
    </div>
</section>
