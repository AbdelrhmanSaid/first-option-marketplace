<x-layouts::website :title="__('Publish a new add-on')">
    <x-form class="container" :action="route('website.publishers.dashboard.addons.store')" method="POST">
        <x-page-header :title="__('Publish a new add-on')" :pretitle="current_publisher()->name" class="mb-3" />

        <div class="card mb-3">
            <div class="card-header">
                <p class="card-title">{{ __('Details') }}</p>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-auto mb-3">
                        <x-avatar :image="asset('assets/images/placeholder.png')" size="xl" icon-preview />
                    </div>

                    <div class="col mb-3">
                        <x-input type="file" name="icon" :title="__('Icon')" :hint="__('Use a square image for best results')"
                            onchange="applyAvatarPreview(this, '[icon-preview]')" validation="required" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="name" :title="__('Name')" :value="old('name')" validation="required" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="short_description" :title="__('Short Description')" :value="old('short_description')" validation="required" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-rich-editor name="description" :title="__('Description')" :value="old('description')" validation="required" />
                </div>

                <div class="mb-3">
                    <x-uploader name="screenshots" :title="__('Screenshots')" :value="old('screenshots')" />
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
                        <x-select name="software_id" :query="\App\Models\Software::class" :value="old('software_id')" :title="__('Software')"
                            validation="required" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-select name="os" :options="\App\Enums\OS::toArray()" :value="old('os')" :title="__('OS')"
                            validation="required" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <x-input type="file" name="resource" :value="old('resource')" :title="__('Resource')" :hint="__('Allowed Extensions: zip rar tar tar.gz gzip tgz pdf msi')"
                            validation="required" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="version" :value="old('version')" :title="__('Version')" validation="required" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-rich-editor name="instructions" :value="old('instructions')" :title="__('Instructions')" />
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
                        <x-input name="price" :value="old('price')" :title="__('Price')" :append="__('EGP')" flat :placeholder="__('Leave empty for free add-on')" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="trial_period" :value="old('trial_period')" :title="__('Trial Period')" :append="__('days')" flat :placeholder="__('Leave empty for no trial')" />
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
                        <x-input name="youtube_video_url" :value="old('youtube_video_url')" :title="__('Youtube Video')" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="privacy_policy_url" :value="old('privacy_policy_url')" :title="__('Privacy Policy')" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="terms_of_service_url" :value="old('terms_of_service_url')" :title="__('Terms of Service')" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-input name="learn_more_url" :value="old('learn_more_url')" :title="__('Learn More')" />
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('website.publishers.dashboard.index', 'addons') }}" class="btn btn-secondary me-2">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Publish') }}</button>
        </div>
    </x-form>
</x-layouts::website>
