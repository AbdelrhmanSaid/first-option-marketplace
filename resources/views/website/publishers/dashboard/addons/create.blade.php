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
                    <x-select name="tags[]" :title="__('Tags')" :options="\App\Models\Addon::tags()" :value="old('tags')" select-max-items="3" tags multiple :hint="__('You can select up to 3 tags')" validation="max:3" />
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
                        <x-select name="category_id" :query="\App\Models\Category::class" :value="old('category_id')" :title="__('Category')"
                            validation="required" />
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <x-select name="discipline_id" :query="\App\Models\Discipline::class" :value="old('discipline_id')" :title="__('Discipline')"
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
                {{-- Subscription Type Selection --}}
                <div class="mb-4">
                    <x-select name="subscription_type" :options="['one_time' => __('One-time Purchase'), 'subscription' => __('Subscription')]" :value="old('subscription_type')" :title="__('Pricing Model')"
                        validation="required" />
                </div>

                {{-- One-time Purchase Pricing --}}
                <div class="pricing-section" data-pricing-type="one_time">
                    <h6 class="mb-3">{{ __('One-time Purchase Pricing') }}</h6>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-input name="price" :value="old('price')" :title="__('Price')" :append="__('EGP')" flat
                                :placeholder="__('Leave empty for free add-on')" />
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <x-input name="trial_period" :value="old('trial_period')" :title="__('Trial Period')" :append="__('days')" flat
                                :placeholder="__('Leave empty for no trial')" />
                        </div>
                    </div>
                </div>

                {{-- Subscription Pricing --}}
                <div class="pricing-section" data-pricing-type="subscription">
                    <h6 class="mb-3">{{ __('Subscription Pricing') }}</h6>

                    <div class="row mb-3">
                        <div class="col-12">
                            <x-input name="trial_period" :value="old('trial_period')" :title="__('Trial Period')" :append="__('days')" flat
                                :placeholder="__('Leave empty for no trial')" />
                            <small class="text-muted">{{ __('Free trial period before subscription starts') }}</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <x-input name="monthly_price" :value="old('monthly_price')" :title="__('Monthly Price')" :append="__('EGP/month')" flat
                                :placeholder="__('0.00')" />
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <x-input name="quarterly_price" :value="old('quarterly_price')" :title="__('Quarterly Price')" :append="__('EGP/3 months')"
                                flat :placeholder="__('0.00')" />
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <x-input name="yearly_price" :value="old('yearly_price')" :title="__('Yearly Price')" :append="__('EGP/year')" flat
                                :placeholder="__('0.00')" />
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('Set at least one subscription price. Leave others empty to disable those subscription periods.') }}
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const subscriptionTypeSelect = document.querySelector('select[name="subscription_type"]');
                    const pricingSections = document.querySelectorAll('.pricing-section');

                    function togglePricingSections() {
                        const selectedType = subscriptionTypeSelect.value;

                        pricingSections.forEach(section => {
                            const sectionType = section.getAttribute('data-pricing-type');
                            if (sectionType === selectedType) {
                                section.style.display = 'block';
                            } else {
                                section.style.display = 'none';
                            }
                        });
                    }

                    // Initial toggle
                    togglePricingSections();

                    // Listen for changes
                    subscriptionTypeSelect.addEventListener('change', togglePricingSections);
                });
            </script>
        @endpush

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
