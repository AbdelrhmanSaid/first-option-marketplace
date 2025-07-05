<x-layouts::website :title="__('Download')">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Download :addon', ['addon' => $subscription->addon->name]) }}</h3>
            </div>

            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>{{ __('Version') }}</th>
                            <th>{{ __('Published At') }}</th>
                            <th class="w-1 text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($subscription->addon->versions as $version)
                            <tr>
                                <td>{{ $version->version }}</td>
                                <td>{{ $version->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-icon">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    {{ __('No versions available.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::website>
