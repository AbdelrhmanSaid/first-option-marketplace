<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.categories.update', $category)" method="PUT">
        <div class="card-header">
            <p class="card-title">{{ __('Edit') }}</p>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-input name="name" :title="__('Name')" :value="$category->name" validation="required" />
            </div>

            <div class="mb-3">
                <x-rich-editor name="description" :title="__('Description')" :value="$category->description" />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.categories.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
