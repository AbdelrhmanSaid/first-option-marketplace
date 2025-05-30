@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($hint)
    <x-hint>{{ $hint }}</x-hint>
@endif

<select id="{{ $id }}" {{ $attributes }}>
    @foreach ($options as $key => $label)
        <option value="{{ $key }}" @selected(in_array($key, $value))>{{ $label }}</option>
    @endforeach

    {{ $slot }}
</select>

@pushOnce('styles')
    <link rel="stylesheet" href="{{ hashed_asset('/vendor/tom-select/tom-select.min.css') }}">
@endPushOnce

@pushOnce('scripts')
    <script src="{{ hashed_asset('/vendor/tom-select/tom-select.complete.min.js') }}"></script>
@endPushOnce
