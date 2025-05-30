<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.users.update', $user)" method="PUT">
        <div class="card-header">
            <p class="card-title">{{ __('Edit') }}</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <x-input name="first_name" :title="__('First name')" :value="$user->first_name" validation="required" />
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <x-input name="last_name" :title="__('Last name')" :value="$user->last_name" validation="required" />
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.users.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
