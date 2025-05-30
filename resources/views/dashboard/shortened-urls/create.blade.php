<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.shortened-urls.store')" method="POST">
        <div class="card-header">
            <p class="card-title">{{ __('Create') }}</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <x-input type="url" name="url" :title="__('Url')" :value="old('url')" validation="required|url" />
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <x-input name="slug" :title="__('Slug')" :value="old('slug', \Illuminate\Support\Str::random(10))" :prepend="route('website.shortened-urls.show') . '/'"
                        validation="nullable|alpha_dash|max:120" />
                </div>
            </div>

            <div class="mb-3">
                <x-input name="title" :title="__('Title')" :value="old('title')" validation="nullable|max:120" />
            </div>

            <div class="mb-3">
                <x-select name="tags[]" :title="__('Tags')" :options="$tags" :value="old('tags')" tags multiple />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.shortened-urls.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
