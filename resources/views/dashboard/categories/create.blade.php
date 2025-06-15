<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.categories.store')" method="POST">
        <div class="card-header">
            <p class="card-title">{{ __('Create') }}</p>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-input name="name" :title="__('Name')" :value="old('name')" validation="required" />
            </div>

            <div class="mb-3">
                <x-rich-editor name="description" :title="__('Description')" :value="old('description')" />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.categories.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
