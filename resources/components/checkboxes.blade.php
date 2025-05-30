@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($hint)
    <x-hint>{{ $hint }}</x-hint>
@endif

<div id="{{ $id }}">
    @foreach ($options as $key => $label)
        <label @class(['form-check', 'form-check-inline' => $inline])>
            <input type="checkbox" class="form-check-input" name="{{ $name }}" value="{{ $key }}"
                @checked(in_array($key, $value)) @disabled(in_array($key, $disabled))
                @if ($validation && $loop->first) validation="{{ $validation }}" validation-container="#{{ $id }}" @endif
                title="{{ $label }}" />
            <span class="form-check-label">{{ $label }}</span>
        </label>
    @endforeach
</div>
