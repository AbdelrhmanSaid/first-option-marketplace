@props([
    'lazy' => true,
])

<img src="{{ setting('app_logo_dark') }}" alt="{{ app_name() }}" loading="{{ $lazy ? 'lazy' : 'eager' }}"
    {{ $attributes->class(['hide-theme-light', 'app-logo']) }} />
<img src="{{ setting('app_logo_light') }}" alt="{{ app_name() }}" loading="{{ $lazy ? 'lazy' : 'eager' }}"
    {{ $attributes->class(['hide-theme-dark', 'app-logo']) }} />
