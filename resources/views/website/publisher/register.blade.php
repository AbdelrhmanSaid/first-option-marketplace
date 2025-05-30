<x-layouts::website.auth :title="__('Register as a publisher')">
    <x-status />

    <x-form class="card card-md" :action="route('website.publisher.register.store')" method="POST">
        <div class="card-body">
            <div class="text-center mb-4">
                <h2 class="h2 mb-2">
                    {{ __('Register as a publisher') }}
                </h2>

                <p class="text-muted">
                    {{ __('Fill your company details to register as a publisher.') }}
                </p>
            </div>

            <div class="mb-3">
                <x-input type="text" name="name" :title="__('Company name')" value="{{ old('name') }}"
                    :placeholder="__('Enter your company name')" validation="required" />
            </div>

            <div class="mb-3">
                <x-input type="email" name="support_email" :title="__('Support email')" value="{{ old('support_email') }}"
                    :placeholder="__('Enter your support email so users can contact you')" validation="required|email" />
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">{{ __('Proceed') }}</button>
            </div>
        </div>
    </x-form>
</x-layouts::website.auth>
