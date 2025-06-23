@push('styles')
    <style>
        .additional-links li i {
            width: 1.5rem;
        }
    </style>
@endpush

@if ($addon->youtube_video_url || $addon->privacy_policy_url || $addon->terms_of_service_url || $addon->learn_more_url)
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title mb-0">{{ __('Additional Links') }}</h3>
        </div>
        <div class="card-body additional-links">
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
