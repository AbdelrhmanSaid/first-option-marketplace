<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.settings.update')" method="PUT">
        <div class="row g-0">
            <div class="col-12 col-md-3 border-end">
                <div class="card-body">
                    <h4 class="subheader">{{ __('Application Settings') }}</h4>

                    <div class="list-group list-group-transparent" id="list-tab" role="tablist">
                        @foreach ($sections as $key => $value)
                            <a href="#{{ $key }}" data-bs-toggle="tab" @class([
                                'list-group-item list-group-item-action d-flex align-items-center',
                                'active' => $loop->first,
                            ])>
                                {{ $value }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-9 d-flex flex-column">
                <div class="card-body">
                    <div class="tab-content">
                        @foreach ($sections as $key => $value)
                            <div @class(['tab-pane fade', 'show active' => $loop->first]) id="{{ $key }}">
                                @include('dashboard.settings.partials.' . $key)
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer bg-transparent mt-auto">
                    <div class="btn-list justify-content-end">
                        <button type="reset" class="btn">{{ __('Reset') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </x-form>
</x-layouts::dashboard>
