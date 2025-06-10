<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.software.update', $software)" method="PUT">
        <div class="card-header">
            <p class="card-title">{{ __('Edit') }}</p>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-input name="name" :title="__('Name')" :value="$software->name" validation="required" />
            </div>

            <div class="mb-3">
                <x-rich-editor name="description" :title="__('Description')" :value="$software->description" />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.software.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
