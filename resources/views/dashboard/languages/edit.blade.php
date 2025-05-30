<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.languages.update', $language)" method="PUT">
        <div class="card-header">
            <p class="card-title">{{ __('Edit') }}</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <x-input name="name" :title="__('Language Name')" :value="$language->name" validation="required" />
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <x-select name="direction" :title="__('Direction')" :value="$language->direction" validation="required"
                        :options="['ltr' => __('Left to Right'), 'rtl' => __('Right to Left')]" />
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.languages.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
