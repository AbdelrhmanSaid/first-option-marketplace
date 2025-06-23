<x-layouts::website :title="$addon->name">
    @push('styles')
        <style>
            .screenshot-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .screenshot-item {
                cursor: pointer;
                border: 1px solid #e0e0e0;
                overflow: hidden;
                width: 120px;
                height: 120px;
            }
        </style>
    @endpush

    <div class="container py-4">
        {{-- Header Card --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                    {{-- Icon --}}
                    <x-avatar :image="$addon->icon" :name="$addon->name" size="xl" />

                    <div class="flex-grow-1">
                        <h1
                            class="h2 mb-0 d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                            {{ $addon->name }}
                        </h1>

                        @if ($addon->short_description)
                            <p class="text-muted text-md-start text-center mb-0">{{ $addon->short_description }}</p>
                        @endif
                    </div>

                    {{-- Pricing --}}
                    <div class="text-md-end pt-md-0">
                        @if ($addon->price)
                            <div class="h2 text-success mb-0">
                                {{ number_format($addon->price, 0) }} {{ __('EGP') }}
                                <small class="h6 text-muted">/{{ __('mo') }}</small>
                            </div>

                            @if ($addon->trial_period)
                                <div class="text-success">
                                    {{ $addon->trial_period }} {{ __('days trial') }}
                                </div>
                            @endif
                        @else
                            <div class="h2 text-success mb-0">{{ __('Free') }}</div>
                        @endif
                    </div>
                </div>

                <hr class="my-4" />

                {{-- Meta Datagrid --}}
                <div class="datagrid">
                    @if ($addon->publisher)
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Publisher') }}</div>
                            <div class="datagrid-content">
                                <a href="{{ route('website.publishers.show', $addon->publisher) }}"
                                    class="text-decoration-none">
                                    {{ $addon->publisher->name }}
                                </a>
                                @if ($addon->publisher->is_verified)
                                    <x-icons.verified class="text-primary" style="width: 14px; height: 14px;" />
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($addon->software)
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Software') }}</div>
                            <div class="datagrid-content">{{ $addon->software->name }}</div>
                        </div>
                    @endif

                    @if ($addon->category)
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Category') }}</div>
                            <div class="datagrid-content">{{ $addon->category->name }}</div>
                        </div>
                    @endif

                    @if ($addon->discipline)
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Discipline') }}</div>
                            <div class="datagrid-content">{{ $addon->discipline->name }}</div>
                        </div>
                    @endif

                    @if ($addon->os)
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('OS') }}</div>
                            <div class="datagrid-content">{{ $addon->os->label() }}</div>
                        </div>
                    @endif

                    @if ($addon->versions->isNotEmpty())
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Latest Version') }}</div>
                            <div class="datagrid-content">
                                {{ optional($addon->versions->sortByDesc('id')->first())->version }}</div>
                        </div>
                    @endif

                    @if ($addon->tags && is_array($addon->tags) && count($addon->tags) > 0)
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Tags') }}</div>
                            <div class="datagrid-content">
                                <div class="tags-list">
                                    @foreach ($addon->tags as $tag)
                                        <span class="tag">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Description --}}
        @if ($addon->description)
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ __('Description') }}</h3>
                </div>
                <div class="card-body">
                    {!! $addon->description !!}

                    {{-- Screenshots --}}
                    @if ($addon->screenshots && is_array($addon->screenshots) && count($addon->screenshots) > 0)
                        <p class="mb-2 fw-bold">{{ __('Screenshots') }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($addon->screenshots as $screenshot)
                                <div class="screenshot-item rounded">
                                    <a href="{{ $screenshot['url'] }}" data-fancybox="screenshots">
                                        <img src="{{ $screenshot['url'] }}" class="img-fluid" alt="screenshot" />
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Additional Links --}}
        @if ($addon->youtube_video_url || $addon->privacy_policy_url || $addon->terms_of_service_url || $addon->learn_more_url)
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ __('Additional Links') }}</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @if ($addon->youtube_video_url)
                            <li class="mb-2">
                                <i class="fab fa-youtube me-1 text-danger"></i>
                                <a href="{{ $addon->youtube_video_url }}" target="_blank">{{ __('Watch Video') }}</a>
                            </li>
                        @endif
                        @if ($addon->privacy_policy_url)
                            <li class="mb-2">
                                <i class="fas fa-user-shield me-1"></i>
                                <a href="{{ $addon->privacy_policy_url }}"
                                    target="_blank">{{ __('Privacy Policy') }}</a>
                            </li>
                        @endif
                        @if ($addon->terms_of_service_url)
                            <li class="mb-2">
                                <i class="fas fa-file-contract me-1"></i>
                                <a href="{{ $addon->terms_of_service_url }}"
                                    target="_blank">{{ __('Terms of Service') }}</a>
                            </li>
                        @endif
                        @if ($addon->learn_more_url)
                            <li class="mb-2">
                                <i class="fas fa-external-link-alt me-1"></i>
                                <a href="{{ $addon->learn_more_url }}" target="_blank">{{ __('Learn More') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif
    </div>
</x-layouts::website>
