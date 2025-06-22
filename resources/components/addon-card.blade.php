@push('styles')
    <style>
        .addon-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--tblr-border-color);
            border-radius: 12px;
            overflow: hidden;
        }

        .addon-card:hover {
            transform: translateY(-4px);
        }

        .addon-card .addon-header {
            padding: 20px 20px 0 20px;
        }

        .addon-card .addon-content {
            padding: 0 20px;
        }

        .addon-card .addon-footer {
            padding: 16px 20px 20px 20px;
            margin-top: auto;
        }

        .addon-card .addon-title {
            font-size: 16px;
            font-weight: 600;
            line-height: 1.3;
            margin: 0;
        }

        .addon-card .addon-meta {
            font-size: 12px;
            color: var(--tblr-muted);
            margin-top: 4px;
        }

        .addon-card .addon-description {
            font-size: 13px;
            color: var(--tblr-muted);
            line-height: 1.4;
            margin: 12px 0 0 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .addon-card .addon-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .addon-card .addon-tag {
            background: var(--tblr-bg-surface-secondary);
            color: var(--tblr-muted);
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .addon-card .addon-price {
            text-align: right;
        }

        .addon-card .price-main {
            font-size: 18px;
            font-weight: 700;
            color: var(--tblr-success);
        }

        .addon-card .price-period {
            font-size: 12px;
            color: var(--tblr-muted);
            margin-left: 2px;
        }

        .addon-card .price-trial {
            font-size: 11px;
            color: var(--tblr-success);
            margin-top: 2px;
        }

        .addon-card .card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 0;
        }
    </style>
@endpush

<div {{ $attributes->class(['card', 'addon-card', 'h-100']) }}>
    <div class="card-body">
        <!-- Header -->
        <div class="addon-header">
            <div class="d-flex align-items-center gap-3">
                <x-avatar :image="$addon->icon" :name="$addon->name" size="md" class="flex-shrink-0" />

                <div class="flex-grow-1 min-w-0">
                    <h5 class="addon-title">
                        <a href="{{ $link }}" class="text-decoration-none stretched-link">
                            {{ $addon->name }}
                        </a>
                    </h5>

                    @if ($showPublisher && $addon->publisher)
                        <div class="addon-meta d-flex align-items-center gap-1">
                            <span>{{ $addon->publisher->name }}</span>
                            @if ($addon->publisher->is_verified)
                                <x-icons.verified class="text-primary" style="width: 12px; height: 12px;" />
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="addon-content flex-grow-1">
            <p class="addon-description">{{ $addon->short_description }}</p>

            <div class="addon-tags">
                @if ($addon->software)
                    <span class="addon-tag">
                        <i class="fas fa-cube" style="font-size: 10px;"></i>
                        {{ $addon->software->name }}
                    </span>
                @endif

                @if ($addon->category)
                    <span class="addon-tag">
                        <i class="fas fa-folder" style="font-size: 10px;"></i>
                        {{ $addon->category->name }}
                    </span>
                @endif

                @if ($addon->discipline)
                    <span class="addon-tag">
                        <i class="fas fa-graduation-cap" style="font-size: 10px;"></i>
                        {{ $addon->discipline->name }}
                    </span>
                @endif

                @if ($addon->os)
                    <span class="addon-tag">
                        <i class="fas fa-desktop" style="font-size: 10px;"></i>
                        {{ $addon->os->label() }}
                    </span>
                @endif

                @if ($addon->versions->isNotEmpty())
                    <span class="addon-tag">
                        <i class="fas fa-tag" style="font-size: 10px;"></i>
                        {{ $version }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="addon-footer">
            <div class="addon-price">
                @if ($addon->price)
                    <div class="price-main">
                        {{ number_format($addon->price, 0) }} {{ __('EGP') }}
                        <span class="price-period">/{{ __('mo') }}</span>
                    </div>

                    @if ($addon->trial_period)
                        <div class="price-trial">
                            {{ $addon->trial_period }} {{ __('days trial') }}
                        </div>
                    @endif
                @else
                    <div class="price-main">{{ __('Free') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
