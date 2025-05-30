@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($hint)
    <x-hint>{{ $hint }}</x-hint>
@endif

<textarea id="{{ $id }}" {{ $attributes->class(['form-control']) }}>{!! $value ?: $slot !!}</textarea>
