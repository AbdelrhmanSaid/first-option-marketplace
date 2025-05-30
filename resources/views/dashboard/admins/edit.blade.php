<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.admins.update', $admin)" method="PUT">
        <div class="card-header">
            <div class="card-title">{{ __('Edit') }}</div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <x-input :title="__('Admin')" :value="$admin->name" disabled />
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <x-select name="role" :title="__('Role')" :options="$roles" :value="$admin->roles()->first()?->name" removable />
                </div>
            </div>

            <div class="mb-3">
                <x-toggle name="active" :title="__('Active')" :value="$admin->active" :on="__('Yes')" :off="__('No')" />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.admins.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
