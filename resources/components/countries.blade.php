@pushOnce('styles')
    <link rel="stylesheet" href="{{ hashed_asset('/vendor/tabler/css/tabler-flags.min.css') }}" />
@endPushOnce

<x-select :query="\App\Models\Country::class" key="code" template="country" same-template {{ $attributes }} />
