<div class="hero-section py-5">
    <div class="container my-5">
        <div class="col-lg-8">
            <h1 class="display-3 mb-4 lh-1">
                {{ __('Find the best apps, plugins, and integrations for your') }}
                <span class="text-primary">{{ __('Autodesk Experience') }}</span>
            </h1>

            <p class="lead fs-2 mb-4">
                {{ __('Discover powerful apps, plugins, and integrations that supercharge your Autodesk workflows.') }}
                {{ __('Build faster, design smarter, and innovate without boundaries.') }}
            </p>

            <x-form :action="route('website.addons.index')" class="position-relative">
                <x-input class="form-control-lg pe-5" name="search" :placeholder="__('Search for add-ons that you need')" />
                <i class="fas fa-search text-primary position-absolute top-50 end-0 translate-middle-y me-3"></i>
            </x-form>
        </div>
    </div>
</div>
