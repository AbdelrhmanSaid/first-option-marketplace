@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($hint)
    <x-hint>{{ $hint }}</x-hint>
@endif

<label class="form-check form-switch">
    <input type="checkbox" id="{{ $id }}" @checked($value)
        {{ $attributes->class(['form-check-input']) }} />
    <span class="form-check-label form-check-label-on">{{ $on ?: __('Enabled') }}</span>
    <span class="form-check-label form-check-label-off">{{ $off ?: __('Disabled') }}</span>
</label>
