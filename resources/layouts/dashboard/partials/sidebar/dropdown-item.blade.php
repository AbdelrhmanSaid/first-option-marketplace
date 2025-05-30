@props(['item'])

<a @class(['dropdown-item', 'active' => $item->active]) href="{{ $item->url }}">
    @if (isset($item->icon))
        <span class="nav-link-icon"><i class="{{ $item->icon }}"></i></span>
    @endif

    {{ $item->title }}
</a>
