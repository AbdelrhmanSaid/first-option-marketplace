<x-layouts::website :title="__('Payment Successful')">
    @push('styles')
        <style>
            .success-card {
                max-width: 600px;
                margin: 0 auto;
            }
            .success-icon {
                font-size: 4rem;
                color: #28a745;
                margin-bottom: 1rem;
            }
        </style>
    @endpush

    <div class="container py-5">
        <div class="success-card">
            <div class="card">
                <div class="card-body text-center">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>

                    <h2 class="mb-3">{{ __('Payment Successful!') }}</h2>
                    <p class="lead text-muted mb-4">
                        {{ __('Thank you for your purchase. Your addon has been added to your library.') }}
                    </p>

                    <!-- Purchase Details -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img
                                    src="{{ $addon->icon }}"
                                    alt="{{ $addon->name }}"
                                    class="avatar avatar-lg"
                                    style="object-fit: cover;"
                                >
                                <div class="flex-grow-1 text-start">
                                    <h4 class="mb-1">{{ $addon->name }}</h4>
                                    <p class="text-muted mb-0">{{ __('by') }} {{ $addon->publisher->name }}</p>
                                </div>
                                <div class="text-end">
                                    <div class="h4 mb-0 text-success">${{ number_format($userAddon->price_paid, 2) }}</div>
                                    <small class="text-muted">{{ __('Paid') }}</small>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="row text-sm">
                                <div class="col-6 text-start">
                                    <strong>{{ __('Payment Reference:') }}</strong>
                                </div>
                                <div class="col-6 text-end">
                                    <code>{{ $reference }}</code>
                                </div>
                            </div>
                            <div class="row text-sm">
                                <div class="col-6 text-start">
                                    <strong>{{ __('Purchase Date:') }}</strong>
                                </div>
                                <div class="col-6 text-end">
                                    {{ $userAddon->created_at->format('M j, Y \a\t g:i A') }}
                                </div>
                            </div>
                            <div class="row text-sm">
                                <div class="col-6 text-start">
                                    <strong>{{ __('Status:') }}</strong>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="badge bg-success text-success-fg">{{ __('Active') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div class="alert-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="text-start">
                                <h4 class="alert-title">{{ __('What\'s Next?') }}</h4>
                                <div class="text-muted">
                                    {{ __('You can now access your purchased addon from your library. Follow the installation instructions provided by the publisher.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="btn-list">
                        <a href="{{ route('website.library.index') }}" class="btn btn-primary">
                            <i class="fas fa-book me-1"></i>
                            {{ __('Go to My Library') }}
                        </a>
                        <a href="{{ route('website.addons.show', $addon) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>
                            {{ __('View Addon Details') }}
                        </a>
                        <a href="{{ route('website.addons.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-search me-1"></i>
                            {{ __('Browse More Add-ons') }}
                        </a>
                    </div>
                </div>

                <div class="card-footer text-center text-muted">
                    <small>
                        {{ __('A confirmation email has been sent to your registered email address.') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-redirect to library after 10 seconds
            setTimeout(function() {
                if (confirm('{{ __("Would you like to go to your library now?") }}')) {
                    window.location.href = '{{ route("website.library.index") }}';
                }
            }, 10000);
        </script>
    @endpush
</x-layouts::website>
