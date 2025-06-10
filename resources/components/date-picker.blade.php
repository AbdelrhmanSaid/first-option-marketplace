@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

<div class="col input-group input-group-flat">
    <input type="text" id="{{ $id }}" autocomplete="off"
        {{ $attributes->class(['form-control'])->merge(['init' => 'litepicker']) }} />

    <span class="input-group-text">
        <x-icon icon="fa fa-calendar-alt" />
    </span>
</div>

@if ($hint)
    <x-hint class="mt-1">{{ $hint }}</x-hint>
@endif

@pushOnce('scripts')
    <script src="{{ hashed_asset('/vendor/litepicker/litepicker.min.js') }}"></script>
@endPushOnce
