<x-layouts::dashboard>
    <x-form class="card" :action="route('dashboard.roles.update', $role)" method="PUT">
        <div class="card-header">
            <p class="card-title">{{ __('Edit') }}</p>
        </div>

        <div class="card-body border-bottom">
            <div class="d-flex align-items-start gap-2">
                <x-input name="name" :value="$role->name" :placeholder="__('Name')" validation="required" />
                <a class="btn btn-icon" onclick="checkPermissions()"><i class="fas fa-check-double"></i></a>
                <a class="btn btn-icon" onclick="uncheckPermissions()"><i class="fas fa-times"></i></a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th style="width: 25%">{{ __('Category') }}</th>
                        <th>{{ __('Permissions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($permissions as $key => $values)
                        <tr>
                            <td>{{ Str::title(str_replace('.', ' ', $key)) }}</td>
                            <td>
                                <div class="form-selectgroup">
                                    @foreach ($values as $permission)
                                        <label class="form-selectgroup-item">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="form-selectgroup-input" @checked(in_array($permission->name, $role->permissions->pluck('name')->toArray()))>
                                            <span class="form-selectgroup-label">
                                                {{ Str::title(Arr::last(explode('.', $permission->name))) }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer text-end">
            <a href="{{ back_or_route('dashboard.roles.index') }}" class="btn">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>

    @push('scripts')
        <script>
            function checkPermissions() {
                $('input[name^="permissions"]').each(function () {
                    $(this).prop('checked', true);
                });
            }

            function uncheckPermissions() {
                $('input[name^="permissions"]').each(function () {
                    $(this).prop('checked', false);
                });
            }
        </script>
    @endpush
</x-layouts::dashboard>
