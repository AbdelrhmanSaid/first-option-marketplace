<?php

namespace App\Livewire\Datatables;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Builder;
use Redot\Datatables\Columns\BadgeColumn;
use Redot\Datatables\Columns\TextColumn;
use Redot\Datatables\Datatable;
use Redot\Datatables\Filters\StringFilter;
use Redot\Datatables\Filters\TernaryFilter;

class Publishers extends Datatable
{
    /**
     * Get the query source of the datatable.
     */
    public function query(): Builder
    {
        return Publisher::query();
    }

    /**
     * Get the columns for the datatable.
     */
    public function columns(): array
    {
        return [
            TextColumn::make()
                ->getter(fn ($value, Publisher $publisher) => component('avatar', ['name' => $publisher->name, 'image' => $publisher->logo]))
                ->exportable(false)
                ->html(),
            TextColumn::make('name', __('Name'))
                ->width('100%', min: '200px')
                ->searchable()
                ->sortable(),
            TextColumn::make('email', __('Email'))
                ->email()
                ->searchable()
                ->sortable(),
            TextColumn::make('website', __('Website'))
                ->url()
                ->searchable()
                ->sortable(),
            BadgeColumn::make('is_verified', __('Verified'))
                ->sortable(),
        ];
    }

    /**
     * Get the filters for the datatable.
     */
    public function filters(): array
    {
        return [
            StringFilter::make('name', __('Name')),
            StringFilter::make('email', __('Email')),
            StringFilter::make('website', __('Website')),
            TernaryFilter::make('is_verified', __('Verified')),
        ];
    }
}
