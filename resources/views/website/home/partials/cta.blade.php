<section class="py-5">
    <div class="container py-5">
        <div class="row gy-5 align-items-center">
            <div class="col-lg-8">
                <h3 class="mb-2">{{ __('Ready to supercharge your Autodesk workflow?') }}</h3>
                <p class="mb-0 lead">
                    {{ __('Join thousands of professionals who trust our marketplace for their add-on needs.') }}
                </p>
            </div>

            <div class="col-lg-4 btn-list justify-content-end">
                <a href="{{ route('website.addons.index') }}" class="btn btn-primary">
                    {{ __('Browse Add-ons') }}
                </a>
                <a href="{{ route('website.publishers.create') }}" class="btn">
                    {{ __('Become a Publisher') }}
                </a>
            </div>
        </div>
    </div>
</section>
