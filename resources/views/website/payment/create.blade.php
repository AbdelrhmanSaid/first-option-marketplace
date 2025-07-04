<x-layouts::website :title="__('Payment Gateway')">
    @push('styles')
        <style>
            .payment-card {
                max-width: 600px;
                margin: 0 auto;
            }
            .payment-summary {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
            .mock-gateway-badge {
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: #ff6b6b;
                color: white;
                padding: 0.25rem 0.75rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: bold;
                transform: rotate(15deg);
            }
        </style>
    @endpush

    <div class="container py-5">
        <div class="payment-card">
            <div class="card position-relative">
                <div class="mock-gateway-badge">
                    {{ __('MOCK PAYMENT') }}
                </div>

                <div class="card-header text-center">
                    <h2 class="mb-0">{{ __('Secure Payment Gateway') }}</h2>
                    <p class="text-muted mb-0">{{ __('Complete your purchase securely') }}</p>
                </div>

                <div class="card-body">
                    <!-- Payment Summary -->
                    <div class="payment-summary">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img
                                src="{{ $addon->icon }}"
                                alt="{{ $addon->name }}"
                                class="avatar avatar-lg"
                                style="object-fit: cover;"
                            >
                            <div class="flex-grow-1">
                                <h4 class="mb-1">{{ $addon->name }}</h4>
                                <p class="text-muted mb-0">{{ __('by') }} {{ $addon->publisher->name }}</p>
                            </div>
                            <div class="text-end">
                                <div class="h3 mb-0 text-primary">${{ number_format($addon->price, 2) }}</div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="row text-sm">
                            <div class="col-6">
                                <strong>{{ __('Item Total:') }}</strong>
                            </div>
                            <div class="col-6 text-end">
                                ${{ number_format($addon->price, 2) }}
                            </div>
                        </div>
                        <div class="row text-sm">
                            <div class="col-6">
                                {{ __('Processing Fee:') }}
                            </div>
                            <div class="col-6 text-end">
                                $0.00
                            </div>
                        </div>
                        <div class="row text-sm">
                            <div class="col-6">
                                {{ __('Tax:') }}
                            </div>
                            <div class="col-6 text-end">
                                $0.00
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-6">
                                <strong>{{ __('Total:') }}</strong>
                            </div>
                            <div class="col-6 text-end">
                                <strong class="text-primary">${{ number_format($addon->price, 2) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Mock Payment Form -->
                    <x-form :action="route('website.payment.process', $addon)" method="POST">
                        <input type="hidden" name="reference" value="{{ $reference }}">

                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="alert-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div>
                                    <h4 class="alert-title">{{ __('Mock Payment Gateway') }}</h4>
                                    <div class="text-muted">
                                        {{ __('This is a simulated payment for demonstration purposes. No real payment will be processed.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mock Credit Card Form -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('Card Number') }}</label>
                            <input type="text" class="form-control" value="4111 1111 1111 1111" readonly>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">{{ __('Expiry Date') }}</label>
                                <input type="text" class="form-control" value="12/25" readonly>
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('CVV') }}</label>
                                <input type="text" class="form-control" value="123" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">{{ __('Cardholder Name') }}</label>
                            <input type="text" class="form-control" value="{{ $user->first_name }} {{ $user->last_name }}" readonly>
                        </div>

                        <!-- Payment Reference -->
                        <div class="mb-4">
                            <label class="form-label">{{ __('Payment Reference') }}</label>
                            <input type="text" class="form-control" value="{{ $reference }}" readonly>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="submit" name="action" value="cancel" class="btn btn-secondary w-100">
                                    <i class="fas fa-times me-1"></i>
                                    {{ __('Cancel Payment') }}
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" name="action" value="pay" class="btn btn-primary w-100">
                                    <i class="fas fa-credit-card me-1"></i>
                                    {{ __('Pay Now') }}
                                </button>
                            </div>
                        </div>
                    </x-form>
                </div>

                <div class="card-footer text-center text-muted">
                    <small>
                        <i class="fas fa-lock me-1"></i>
                        {{ __('Your payment information is secure and encrypted') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</x-layouts::website>
