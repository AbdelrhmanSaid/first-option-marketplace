<x-layouts::website :title="$staticPage->title">
    <div class="container">
        <div class="page-header mb-5">
            <div class="mb-1">
                <ol class="breadcrumb" aria-label="breadcrumbs">
                    <li class="breadcrumb-item"><a href="{{ route('website.index') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href=".">{{ __('Pages') }}</a>
                    </li>
                </ol>
            </div>

            <h2 class="page-title">
                <span class="text-truncate">{{ $staticPage->title }}</span>
            </h2>
        </div>

        <div class="my-5">
            @if ($staticPage->content)
                {!! $staticPage->content !!}
            @else
                <x-empty />
            @endif
        </div>
    </div>
</x-layouts::website>