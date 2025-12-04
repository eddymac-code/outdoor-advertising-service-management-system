<?php

namespace App\Livewire;

use App\Models\Client;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ClientTable extends PowerGridComponent
{
    public string $tableName = 'clientTable';
    private int $rowNumber = 0;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Client::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        // Current page
        $page = (int) request()->query('page', 1);

        // Get per-page from PowerGrid state (default 10 if not set)
        $perPage = (int) request()->query('perPage', 10);

        // Seed row counter so numbering continues across pages
        $this->rowNumber = ($page - 1) * $perPage;

        return PowerGrid::fields()
            ->add('index', function (Client $model) {
                return ++$this->rowNumber;
            })
            ->add('name')
            ->add('company')
            ->add('email')
            ->add('phone')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'index'),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Company', 'company')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),

            // Column::make('Created at', 'created_at_formatted', 'created_at')
            //     ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    // #[\Livewire\Attributes\On('edit')]
    // public function edit($rowId): void
    // {
    //     $this->js('alert('.$rowId.')');
    // }

    public function actions(Client $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('bg-blue-700 px-3 py-1 rounded-lg hover:bg-blue-500')
                ->dispatch('edit-client', ['clientId' => $row->id]),
            Button::add('delete')
                ->confirm('Are you sure you want to delete this client?')
                ->slot('Delete')
                ->id()
                ->class('bg-red-800 rounded-md px-2 py-1 hover:bg-red-600 disabled:cursor-not-allowed')
                ->dispatch('delete-client', ['clientId' => $row->id]),
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
