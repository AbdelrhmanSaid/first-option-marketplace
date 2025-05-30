<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.languages.store')" method="POST">
        <div class="card-header">
            <p class="card-title">{{ __('Create') }}</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <x-input name="name" :title="__('Language Name')" :value="old('name')" validation="required" />
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <x-input name="code" :title="__('Language Code')" :value="old('code')" validation="required" />
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <x-select name="source" :title="__('Source')" :options="config('app.locales')" :value="old('source', config('app.fallback_locale'))"
                        validation="required" />
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <x-select name="direction" :title="__('Direction')" :value="old('direction', 'ltr')" validation="required"
                        :options="['ltr' => __('Left to Right'), 'rtl' => __('Right to Left')]" />
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.languages.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
