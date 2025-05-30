@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($hint)
    <x-hint>{{ $hint }}</x-hint>
@endif

<div id="{{ $id }}">
    @foreach ($options as $key => $color)
        <label class="form-colorinput">
            <input type="radio" class="form-colorinput-input" name="{{ $name }}" value="{{ $key }}"
                @checked($key == $value) @disabled(in_array($key, $disabled))
                @if ($validation && $loop->first) validation="{{ $validation }}" validation-container="#{{ $id }}" @endif />
            <span class="form-colorinput-color" @style(["background: $color"])></span>
        </label>
    @endforeach
</div>
