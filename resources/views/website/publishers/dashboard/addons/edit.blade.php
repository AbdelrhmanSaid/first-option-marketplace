<x-layouts::website :title="__('Edit Add-on')">
    <x-form class="container" :action="route('website.publishers.dashboard.addons.update', $addon)" method="POST">
        @method('PUT')
        <x-page-header :title="__('Edit Add-on')" :pretitle="current_publisher()->name" class="mb-3" />

        <div class="card mb-3">
            <div class="card-header">
                <p class="card-title">{{ __('Details') }}</p>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-auto mb-3">
                        <x-avatar :image="$addon->icon" size="xl" icon-preview />
                    </div>

                    <div class="col mb-3">
                        <x-input type="file" name="icon" :title="__('Icon')" :hint="__('Use a square image for best results')"
                            onchange="applyAvatarPreview(this, '[icon-preview]')" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="name" :title="__('Name')" :value="old('name', $addon->name)" validation="required" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="short_description" :title="__('Short Description')" :value="old('short_description', $addon->short_description)" validation="required" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-rich-editor name="description" :title="__('Description')" :value="old('description', $addon->description)" validation="required" />
                </div>

                <div class="mb-3">
                    <x-select name="tags[]" :title="__('Tags')" :options="\App\Models\Addon::tags()" :value="old('tags', $addon->tags)" select-max-items="3" tags multiple :hint="__('You can select up to 3 tags')" validation="max:3" />
                </div>

                <div class="mb-3">
                    <x-uploader name="screenshots" :title="__('Screenshots')" :value="old('screenshots', $addon->screenshots)" />
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <p class="card-title">{{ __('Requirements') }}</p>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <x-select name="software_id" :query="\App\Models\Software::class" :value="old('software_id', $addon->software_id)" :title="__('Software')"
                            validation="required" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-select name="os" :options="\App\Enums\OS::toArray()" :value="old('os', $addon->os->value)" :title="__('OS')"
                            validation="required" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <x-select name="category_id" :query="\App\Models\Category::class" :value="old('category_id', $addon->category_id)" :title="__('Category')"
                            validation="required" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-select name="discipline_id" :query="\App\Models\Discipline::class" :value="old('discipline_id', $addon->discipline_id)" :title="__('Discipline')"
                            validation="required" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-rich-editor name="instructions" :value="old('instructions', $addon->instructions)" :title="__('Instructions')" />
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <p class="card-title">{{ __('Pricing') }}</p>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="price" :value="old('price', $addon->price)" :title="__('Price')" :append="__('EGP per month')" flat :placeholder="__('Leave empty for free add-on')" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="trial_period" :value="old('trial_period', $addon->trial_period)" :title="__('Trial Period')" :append="__('days')" flat :placeholder="__('Leave empty for no trial')" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <p class="card-title">{{ __('Additional Links') }}</p>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="youtube_video_url" :value="old('youtube_video_url', $addon->youtube_video_url)" :title="__('Youtube Video')" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="privacy_policy_url" :value="old('privacy_policy_url', $addon->privacy_policy_url)" :title="__('Privacy Policy')" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="terms_of_service_url" :value="old('terms_of_service_url', $addon->terms_of_service_url)" :title="__('Terms of Service')" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="learn_more_url" :value="old('learn_more_url', $addon->learn_more_url)" :title="__('Learn More')" />
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('website.publishers.dashboard.index', 'addons') }}" class="btn btn-secondary me-2">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>
</x-layouts::website>
