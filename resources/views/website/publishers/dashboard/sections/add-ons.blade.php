<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">{{ __('Add-ons') }}</h3>

        <a href="{{ route('website.publishers.dashboard.addons.create') }}" class="btn btn-icon btn-primary">
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th class="w-1"></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($publisher->addons as $addon)
                    <tr>
                        <td>{{ $addon->name }}</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">{{ __('No add-ons found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer text-muted">
        {{ __('Publisher account has :count add-on(s)', ['count' => $publisher->addons->count()]) }}
    </div>
</div>
