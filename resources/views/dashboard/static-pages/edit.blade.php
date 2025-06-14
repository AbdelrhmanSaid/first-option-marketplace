<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.static-pages.update', $staticPage)" method="PUT">
        <div class="card-header">
            <p class="card-title">{{ __('Edit') }}</p>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-translatable component="input" name="title" :value="$staticPage->getTranslations('title')" :title="__('Title')" validation="required" />
            </div>

            <div class="mb-3">
                <x-translatable component="rich-editor" name="content" :value="$staticPage->getTranslations('content')" :title="__('Content')" />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.static-pages.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>