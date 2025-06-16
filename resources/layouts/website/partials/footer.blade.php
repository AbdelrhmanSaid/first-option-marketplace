<footer>
    <footer class="container py-5">
        <div class="row">
            <div class="col-lg-6">
                <x-logo class="mb-3" />

                <p class="mb-4">
                    {{ __('Breakthrough in the Technology of Engineering Services') }}
                </p>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <h5>{{ __('Quick Links') }}</h5>

                <ul class="nav flex-column">
                    @foreach (\App\Models\StaticPage::select('title', 'slug')->get() as $page)
                        <li class="nav-item mb-2">
                            <a href="{{ route('website.static-pages.show', $page->slug) }}"
                                class="nav-link p-0 text-body-secondary">
                                {{ $page->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <h5>{{ __('Reach Us') }}</h5>

                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="tel:+201004186970" class="nav-link p-0">
                            <i class="fa fa-phone me-2"></i>
                            <span>+201004186970</span>
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="mailto:info@firstoption.com" class="nav-link p-0">
                            <i class="fa fa-envelope me-2"></i>
                            <span>info@firstoption.com</span>
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="javascript:void(0)" class="nav-link p-0">
                            <i class="fa fa-map-marker-alt me-2"></i>
                            <span>42 Ali Amer, Nasr City, Cairo, Egypt</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between text-center pt-4 mt-4 border-top">
            <p>
                &copy; {{ date('Y') }}
                {{ __('All rights reserved for') }}
                <a href="{{ url('/') }}">{{ app_name() }}</a>
            </p>

            <ul class="list-unstyled d-flex justify-content-center">
                <li class="ms-3">
                    <a class="text-muted" href="https://web.facebook.com/TEAServAcademy">
                        <i class="fab fa-facebook"></i>
                    </a>
                </li>

                <li class="ms-3">
                    <a class="text-muted" href="https://www.linkedin.com/company/firstoption-es">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </li>
            </ul>
        </div>
    </footer>
    </div>
