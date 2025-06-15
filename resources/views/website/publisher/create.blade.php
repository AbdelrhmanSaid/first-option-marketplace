<x-layouts::website.compact :title="__('Become a Publisher')">
    <x-form class="card card-md" :action="route('website.publisher.store')" method="POST">
        <div class="card-body">
            <h2>{{ __('Become a Publisher') }}</h2>
            <p class="text-muted mb-4">{{ __('Join us as a publisher and start sharing your content with the world.') }}
            </p>

            <div class="mb-3">
                <x-input name="name" :title="__('Display Name')" :placeholder="__('How your name will appear on the site')" validation="required" />
            </div>

            <div class="mb-3">
                <x-input name="headline" :title="__('Headline')" :placeholder="__('A short description of who you are')" />
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <x-input name="email" type="email" :title="__('Contact Email')" :placeholder="__('Your email address for contact')" validation="required|email" />
                </div>
                <div class="col-md-6">
                    <x-input name="website" type="url" :title="__('Website')" :placeholder="__('Your personal or professional website')" />
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">{{ __('Join') }}</button>
        </div>
    </x-form>
</x-layouts::website.compact>
