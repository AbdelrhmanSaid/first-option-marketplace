<?php

namespace App\Livewire\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Redot\Datatables\Actions\Action;
use Redot\Datatables\Columns\TextColumn;
use Redot\Datatables\Datatable;

class StaticPages extends Datatable
{
    /**
     * Get the query source of the datatable.
     */
    public function query(): Builder
    {
        return \App\Models\StaticPage::query();
    }

    /**
     * Get the columns for the datatable.
     */
    public function columns(): array
    {
        return [
            TextColumn::make('title', __('Title'))
                ->sortable()
                ->searchable(),
        ];
    }

    /**
     * Get the actions for the datatable.
     */
    public function actions(): array
    {
        return [
            Action::edit('dashboard.static-pages.edit')->visible(route_allowed('dashboard.static-pages.edit')),
        ];
    }
}