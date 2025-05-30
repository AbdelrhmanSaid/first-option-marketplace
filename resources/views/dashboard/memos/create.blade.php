<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.memos.store')" method="POST">
        <div class="card-header">
            <p class="card-title">{{ __('Create') }}</p>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-input name="title" :title="__('Title')" :value="old('title')" validation="required" />
            </div>

            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <x-icon-picker name="icon" :title="__('Icon')" :value="old('icon', 'far fa-note-sticky')" />
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <x-date-picker name="date" :title="__('Date')" :value="old('date', now())" />
                </div>
            </div>

            <div class="mb-3">
                <x-rich-editor name="content" :title="__('Content')" :value="old('content')" />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.memos.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
