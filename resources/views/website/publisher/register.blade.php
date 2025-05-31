<x-layouts::website.auth :title="__('Register as a publisher')">
    <x-status />

    <x-form class="card card-md" :action="route('website.publisher.register.store')" method="POST">
        <div class="card-body">
            <div class="text-center mb-4">
                <h2 class="h2 mb-2">
                    {{ __('Become a publisher') }}
                </h2>

                <p class="text-muted">
                    {{ __('Fill your details to register as a publisher.') }}
                </p>
            </div>

            <div class="row">
                <div class="col-auto mb-3">
                    <x-avatar :name="old('name', 'Publisher')" :image="old('logo')" size="xl" logo-preview />
                </div>

                <div class="col mb-3">
                    <x-input type="file" name="logo" :title="__('Publisher Logo')"
                        onchange="applyAvatarPreview(this, '[logo-preview]')" />

                    <small class="form-hint mt-1">
                        {{ __('Use a square image for best results.') }}
                    </small>
                </div>
            </div>

            <div class="mb-3">
                <x-input type="text" name="name" :title="__('Publisher Name')" value="{{ old('name') }}"
                    validation="required" />

                <small class="form-hint mt-1">
                    {{ __('Use your company name or a unique name for your publisher profile, you can change it later.') }}
                </small>

                @push('scripts')
                    <script>
                        $('[name="name"]').on('input', function() {
                            let name = $(this).val() || 'Publisher';
                            $('[logo-preview]').text(name.charAt(0));
                        });
                    </script>
                @endpush
            </div>

            <div class="mb-3">
                <x-input type="email" name="support_email" :title="__('Support email')" value="{{ old('support_email') }}"
                    validation="required|email" />

                <small class="form-hint mt-1">
                    {{ __('This will be used to contact you for any issues or questions.') }}
                </small>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">{{ __('Proceed') }}</button>
            </div>
        </div>
    </x-form>
</x-layouts::website.auth>
