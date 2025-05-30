<div class="card mb-2">
    <div class="card-header">
        <span class="text-muted cursor-grab" role="button" sortable-handle>
            <i class="fas fa-grip"></i>
        </span>

        <div class="card-actions btn-actions">
            <button type="button" class="btn-action" action="insert" role="button" title="{{ __('Insert') }}">
                <x-icon icon="fas fa-plus" />
            </button>

            <button type="button" class="btn-action" action="remove" role="button" title="{{ __('Remove') }}">
                <x-icon icon="fas fa-times" />
            </button>
        </div>
    </div>

    <div {{ $attributes->class(['card-body']) }}>
        {{ $slot }}
    </div>
</div>
