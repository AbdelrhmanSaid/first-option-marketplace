@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($hint)
    <x-hint>{{ $hint }}</x-hint>
@endif

<div class="row g-2 w-100" iconpicker-wrapper>
    <div class="col input-group">
        <span class="input-group-text text-body">
            <i class="icon icon-sm {{ $attributes->get('value') }}" iconpicker-preview></i>
        </span>

        <input type="text" id="{{ $id }}" autocomplete="off"
            {{ $attributes->class(['form-control'])->merge(['init' => 'icon-picker']) }} />
    </div>

    <div class="col-auto">
        <button type="button" class="btn btn-icon" tabindex="-1" iconpicker-picker>
            <i class="fa fa-search"></i>
        </button>
    </div>
</div>

@pushOnce('templates')
    <template for="icon-picker">
        <div class="icon-picker">
            <input type="text" class="form-control" placeholder="{{ __('Search for an icon') }}..." iconpicker-search />

            <div iconpicker-list-wrapper>
                <div iconpicker-list>
                    <p class="empty text-center text-muted" style="grid-column: 1 / -1;">
                        {{ __('No icons found.') }}
                    </p>
                </div>
            </div>
        </div>
    </template>
@endPushOnce
