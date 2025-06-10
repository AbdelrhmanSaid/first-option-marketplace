@if ($title && $floating === false)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($floating)
    <div class="form-floating">
        <select id="{{ $id }}" {{ $attributes->class(['form-select']) }}>
            @foreach ($options as $key => $label)
                <option value="{{ $key }}" @selected(in_array($key, $value))>{{ $label }}</option>
            @endforeach

            {{ $slot }}
        </select>

        @if ($title)
            <x-label :title="$title" :for="$id" :required="$required" />
        @endif
    </div>
@else
    <select id="{{ $id }}" {{ $attributes }}>
        @foreach ($options as $key => $label)
            <option value="{{ $key }}" @selected(in_array($key, $value))>{{ $label }}</option>
        @endforeach

        {{ $slot }}
    </select>
@endif

@if ($hint)
    <x-hint class="mt-1">{{ $hint }}</x-hint>
@endif

@if ($tom)
    @pushOnce('styles')
        <link rel="stylesheet" href="{{ hashed_asset('/vendor/tom-select/tom-select.min.css') }}">
    @endPushOnce

    @pushOnce('scripts')
        <script src="{{ hashed_asset('/vendor/tom-select/tom-select.complete.min.js') }}"></script>
    @endPushOnce
@endif
