@php
    $members = current_user()->publisher->members;
    $canDelete = in_array(current_user()->member_role, [
        \App\Enums\PublisherMemberRole::Admin,
        \App\Enums\PublisherMemberRole::Owner,
    ]);
@endphp

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">{{ __('Members') }}</h3>
        <button type="button" class="btn btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#add-member-modal">
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <div class="table-responsive">
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Role') }}</th>

                    @if ($canDelete)
                        <th class="w-1"></th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach ($members as $member)
                    <tr>
                        <td>{{ $member->user->full_name }}</td>
                        <td>{{ $member->user->email }}</td>
                        <td>{{ $member->role }}</td>

                        @if ($canDelete)
                            <td>
                                @if ($member->user_id !== current_user()->id && $member->role !== \App\Enums\PublisherMemberRole::Owner)
                                    <button type="button" class="btn btn-icon text-danger"
                                        onclick="confirmMemberDelete('{{ $member->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer text-muted">
        {{ __('Publisher account has :count team member(s)', ['count' => $members->count()]) }}
    </div>
</div>

<div class="modal fade" id="add-member-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <x-form class="modal-content" action="{{ route('website.publisher.members.store') }}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Member') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <x-input name="first_name" type="text" :title="__('First Name')" validation="required" />
                    </div>

                    <div class="col-6">
                        <x-input name="last_name" type="text" :title="__('Last Name')" validation="required" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <x-input name="email" type="email" :title="__('Email')" validation="required|email" />
                    </div>

                    <div class="col-6">
                        <x-select name="role" :tom="false" :title="__('Role')" :options="['member' => __('Member'), 'admin' => __('Admin')]"
                            validation="required" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Add Member') }}</button>
            </div>
        </x-form>
    </div>
</div>

@push('scripts')
    <script>
        function confirmMemberDelete(id) {
            warnBeforeAction(() => {
                const endpoint = '{{ route('website.publisher.members.destroy', ':id') }}';
                formRequest(endpoint.replace(':id', id), {}, 'DELETE');
            });
        }
    </script>
@endpush
