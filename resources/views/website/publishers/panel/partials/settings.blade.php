<x-form class="card" :action="route('website.publishers.panel.settings.update')" method="PUT">
    <div class="card-header">
        <p class="card-title">{{ __('Settings') }}</p>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-auto mb-3">
                <x-avatar :name="$publisher->name" :image="$publisher->logo" size="xl" avatar-preview />
            </div>

            <div class="col mb-3">
                <x-input type="file" name="logo" :title="__('Logo')" :hint="__('Use a square image for best results')"
                    onchange="applyAvatarPreview(this, '[avatar-preview]')" />
            </div>
        </div>

        <div class="mb-3">
            <x-input name="name" :title="__('Display Name')" :value="$publisher->name" validation="required" />
        </div>

        <div class="mb-3">
            <x-input name="headline" :title="__('Headline')" :value="$publisher->headline" validation="required" />
        </div>

        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <x-input name="email" type="email" :title="__('Contact Email')" :value="$publisher->email"
                    validation="required|email" />
            </div>

            <div class="col-12 col-md-6 mb-3">
                <x-input name="website" type="url" :title="__('Website')" :value="$publisher->website" />
            </div>
        </div>
    </div>

    <div class="card-footer text-end">
        <a href="{{ back_or_route('dashboard.memos.index') }}" class="btn">{{ __('Back') }}</a>
        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
    </div>
</x-form>
