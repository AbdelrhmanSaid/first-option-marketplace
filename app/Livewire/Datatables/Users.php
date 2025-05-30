<?php

namespace App\Livewire\Datatables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Redot\Datatables\Actions\Action;
use Redot\Datatables\Columns\BadgeColumn;
use Redot\Datatables\Columns\TextColumn;
use Redot\Datatables\Datatable;
use Redot\Datatables\Filters\StringFilter;
use Redot\Datatables\Filters\TernaryFilter;
use Redot\Datatables\Filters\TrashedFilter;

class Users extends Datatable
{
    /**
     * Get the query source of the datatable.
     */
    public function query(): Builder
    {
        return User::query();
    }

    /**
     * Get the columns for the datatable.
     */
    public function columns(): array
    {
        return [
            TextColumn::make('full_name', __('Name'))
                ->width('100%')
                ->minWidth('300px')
                ->searchable()
                ->sortable(),
            TextColumn::make('email', __('Email'))
                ->width('300px')
                ->email()
                ->searchable(),
            BadgeColumn::make('email_verified_at', __('Verified')),
        ];
    }

    /**
     * Get the actions for the datatable.
     */
    public function actions(): array
    {
        return Datatable::defaultActionGroup([
            Action::view('dashboard.users.show')->visible(route_allowed('dashboard.users.show')),
            Action::edit('dashboard.users.edit')->visible(route_allowed('dashboard.users.edit')),
            Action::delete('dashboard.users.destroy')->visible(route_allowed('dashboard.users.destroy')),
        ]);
    }

    /**
     * Get the filters for the datatable.
     */
    public function filters(): array
    {
        return [
            StringFilter::make('full_name', __('Name')),
            StringFilter::make('email', __('Email')),
            TernaryFilter::make(label: __('Verified'))
                ->queries(
                    yes: fn (Builder $query) => $query->whereNotNull('email_verified_at'),
                    no: fn (Builder $query) => $query->whereNull('email_verified_at')
                ),
            TrashedFilter::make(),
        ];
    }
}
