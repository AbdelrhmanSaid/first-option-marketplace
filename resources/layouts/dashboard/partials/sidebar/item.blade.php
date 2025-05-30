@props(['item'])

<li @class(['nav-item', 'active' => $item->active])>
    <a class="nav-link" href="{{ $item->url }}" @if ($item->external) target="_blank" @endif>
        @if (isset($item->icon))
            <span class="nav-link-icon">
                <x-icon :icon="$item->icon" />
            </span>
        @endif

        <span class="nav-link-title me-2">
            {{ $item->title }}
        </span>
    </a>
</li>
