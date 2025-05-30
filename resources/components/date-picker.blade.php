@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($hint)
    <x-hint>{{ $hint }}</x-hint>
@endif

<div class="col input-group input-group-flat has-validation">
    <input type="text" id="{{ $id }}" autocomplete="off"
        {{ $attributes->class(['form-control'])->merge(['init' => 'litepicker']) }} />

    <span class="input-group-text">
        <x-icon icon="fa fa-calendar-alt" />
    </span>
</div>

@pushOnce('scripts')
    <script src="{{ hashed_asset('/vendor/litepicker/litepicker.min.js') }}"></script>
@endPushOnce
