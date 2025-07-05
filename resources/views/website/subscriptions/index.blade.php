<x-layouts::website :title="__('Subscriptions')">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Your Subscriptions') }}</h3>
            </div>

            <div class="table-responsive">
                @include('website.subscriptions.partials.subscriptions-table')
            </div>
        </div>
    </div>
</x-layouts::website>
