<x-layouts::dashboard inline>
    @if ($token->value !== $token->original_translation)
        <x-alert type="info">
            <p>{{ __('This token has been modified. The original translation was') }}:</p>
            <strong>"{{ $token->original_translation }}"</strong>
        </x-alert>
    @endif

    <x-form class="card" :action="route('dashboard.languages.tokens.update', [$language, $token])" method="PUT">
        <div class="card-header">
            <p class="card-title">{{ __('Edit') }}</p>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-input :title="__('Translation Key')" :value="$token->key" disabled />
            </div>

            <div class="mb-3">
                <x-input :title="__('Current Translation')" :value="$token->value" disabled />
            </div>

            <div class="row align-items-end gx-2 mb-3">
                <div class="col">
                    <x-input :title="__('New Translation')" name="value" :value="old('value', $token->value)" />
                </div>

                <div class="col-auto">
                    <button type="button" class="btn btn-icon" auto-translate>
                        <i class="fa fa-language"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>

    @push('scripts')
        <script>
            $('[auto-translate]').on('click', async function () {
                const key = '{{ $token->key }}';
                const value = $('[name="value"]');

                try {
                    const endpoint = '{{ route('dashboard.languages.tokens.translate', [$language, $token]) }}';
                    const response = await fetch(endpoint);
                    const data = await response.json();

                    value.val(data.translation);
                    toastify().success('{{ __('Token translated successfully.') }}');
                } catch (error) {
                    toastify().error('{{ __('An error occurred while translating the token.') }}');
                }
            });
        </script>
    @endpush
</x-layouts::dashboard>
