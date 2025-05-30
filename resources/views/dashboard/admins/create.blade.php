<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.admins.store')" method="POST">
        <div class="card-header">
            <p class="card-title">{{ __('Create') }}</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <x-input name="name" :title="__('Name')" :value="old('name')" validation="required" />
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <x-select name="role" :title="__('Role')" :options="$roles" :value="old('role', null)" removable />
                </div>
            </div>

            <div class="mb-3">
                <x-input type="email" name="email" :title="__('Email address')" :value="old('email')"
                    validation="required|email" />
            </div>

            <div class="mb-3">
                <x-toggle name="active" :title="__('Active')" :value="old('active', true)" :on="__('Yes')" :off="__('No')" />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.admins.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
