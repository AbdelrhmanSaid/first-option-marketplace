@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($hint)
    <x-hint>{{ $hint }}</x-hint>
@endif

<div class="rating-field" id="{{ $id }}" style="--star-size: {{ $size }};">
    @for ($i = $stars; $i > 0; $i--)
        <input type="radio" name="{{ $name }}" id="{{ "$id-$i" }}" value="{{ $i }}"
            @checked($i == $value) />

        <label for="{{ "$id-$i" }}">
            <x-icon :icon="$icon" />
        </label>
    @endfor
</div>
