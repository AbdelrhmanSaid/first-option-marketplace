@if ($title)
    <x-label :title="$title" :for="$id" :required="$required" />
@endif

@if ($hint)
    <x-hint>{{ $hint }}</x-hint>
@endif

<input type="text" data-coloris id="{{ $id }}" {{ $attributes->class(['form-control']) }} />

@pushOnce('styles')
    <link rel="stylesheet" href="{{ hashed_asset('/vendor/coloris/coloris.min.css') }}">
@endPushOnce

@pushOnce('scripts')
    <script src="{{ hashed_asset('/vendor/coloris/coloris.min.js') }}"></script>

    <script>
        $(document).ready(() => {
            const reinitColoris = () => {
                window.Coloris({
                    rtl: $('html').attr('dir') === 'rtl',
                    themeMode: localStorage.getItem(window.themerKey),
                    theme: 'polaroid',
                    formatToggle: true,
                });
            };

            // Initial call
            reinitColoris();

            // Listen for theme changes
            document.addEventListener('theme:changed', reinitColoris);
        });
    </script>
@endPushOnce
