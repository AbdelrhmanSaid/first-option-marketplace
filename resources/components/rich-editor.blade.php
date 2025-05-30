@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($hint)
    <x-hint>{{ $hint }}</x-hint>
@endif

<x-textarea :value="$value" :id="$id" :autosize="false" init="tinymce"
    {{ $attributes->only(['name', 'validation']) }} />

@pushOnce('scripts')
    <script src="{{ hashed_asset('/vendor/tinymce/tinymce.min.js') }}"></script>
@endPushOnce
