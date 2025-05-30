<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.memos.update', $memo)" method="PUT">
        <div class="card-header">
            <p class="card-title">{{ __('Edit') }}</p>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-input name="title" :title="__('Title')" :value="$memo->title" validation="required" />
            </div>

            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <x-icon-picker name="icon" :title="__('Icon')" :value="$memo->icon" />
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <x-date-picker name="date" :title="__('Date')" :value="$memo->date" />
                </div>
            </div>

            <div class="mb-3">
                <x-rich-editor name="content" :title="__('Content')" :value="$memo->content" />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.memos.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>
</x-layouts::dashboard>
