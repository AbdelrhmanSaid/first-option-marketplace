<?php

namespace App\Livewire\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Redot\Datatables\Actions\Action;
use Redot\Datatables\Columns\TextColumn;
use Redot\Datatables\Datatable;

class Disciplines extends Datatable
{
    /**
     * Get the query source of the datatable.
     */
    public function query(): Builder
    {
        return \App\Models\Discipline::query();
    }

    /**
     * Get the columns for the datatable.
     */
    public function columns(): array
    {
        return [
            TextColumn::make('name', __('Name'))
                ->width('100%', min: '300px')
                ->searchable()
                ->sortable(),
        ];
    }

    /**
     * Get the actions for the datatable.
     */
    public function actions(): array
    {
        return [
            Action::edit('dashboard.disciplines.edit')->visible(route_allowed('dashboard.disciplines.edit')),
            Action::delete('dashboard.disciplines.destroy')->visible(route_allowed('dashboard.disciplines.destroy')),
        ];
    }
}
