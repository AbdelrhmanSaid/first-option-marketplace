<?php

namespace App\Livewire\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Redot\Datatables\Columns\TextColumn;
use Redot\Datatables\Datatable;
use Redot\Datatables\Actions\Action;

class Categories extends Datatable
{
    /**
     * Get the query source of the datatable.
     */
    public function query(): Builder
    {
        return \App\Models\Category::query();
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
            Action::edit('dashboard.categories.edit')->visible(route_allowed('dashboard.categories.edit')),
            Action::delete('dashboard.categories.destroy')->visible(route_allowed('dashboard.categories.destroy')),
        ];
    }
}
