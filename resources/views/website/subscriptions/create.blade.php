@php
    $frequencies = [
        1 => __('month'),
        3 => __('quarter'),
        12 => __('year'),
    ];
@endphp

<x-layouts::website.compact :title="__('Checkout')">
    <x-status />

    @push('styles')
        <style>
            [frequency].active {
                border-color: var(--tblr-primary);
                background-color: var(--tblr-primary-lt) !important;
            }

            [frequency] {
                cursor: pointer;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function selectFrequency(frequency) {
                $('[frequency]').removeClass('active');
                $(`[frequency="${frequency}"]`).addClass('active');

                $('#frequency').val(frequency);
            }
        </script>
    @endpush

    <x-form class="card card-md" :action="route('website.subscriptions.store', $addon)" method="POST" dont-toast>
        <input type="hidden" name="frequency" id="frequency" value="1">

        <div class="card-body">
            <h2 class="card-title mb-2">{{ __('Complete Your Purchase') }}</h2>
            <p class="text-muted mb-4">{{ __('Select the frequency of your subscription') }}</p>

            @foreach ($frequencies as $frequency => $frequencyName)
                <div class="card bg-light mb-3 {{ $loop->first ? 'active' : '' }}" onclick="selectFrequency({{ $frequency }})" frequency="{{ $frequency }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-1">{{ $addon->name }}</h3>
                                <p class="text-muted mb-0">{{ $addon->publisher->name }}</p>
                            </div>

                            <div class="col-auto text-end">
                                <div class="text-end">
                                    <div class="h3 mb-0">
                                        <span id="price">{{ number_format($addon->price * $frequency, 2) }}</span>
                                        {{ __('EGP') }}
                                    </div>

                                    <small class="text-muted">
                                        {{ __('every') }} {{ $frequencyName }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="btn-list justify-content-end mt-4">
                <a href="{{ route('website.addons.show', $addon) }}" class="btn">
                    {{ __('Back') }}
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-dollar-sign me-2"></i>
                    {{ __('Pay Now') }}
                </button>
            </div>
        </div>
    </x-form>
</x-layouts::website.compact>
