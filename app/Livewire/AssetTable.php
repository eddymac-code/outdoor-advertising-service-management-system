<?php

namespace App\Livewire;

use App\Models\Asset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AssetTable extends PowerGridComponent
{
    public string $tableName = 'assetTable';
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
        return Asset::query();
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
            ->add('index', function (Asset $model) {
                return ++$this->rowNumber;
            })
            ->add('name')
            ->add('type', function ($entry) {
                return ucfirst($entry->type);
            })
            ->add('location')
            ->add('status')
            ->add('status_formatted', function ($entry) {
                if ($entry->status === 'available') {
                    $stat = Blade::render(<<<blade
                                <x-badge emerald label="Available" />
                            blade);
                } elseif ($entry->status === 'on_hold') {
                    $stat = Blade::render(<<<blade
                                <x-badge amber label="On-Hold" />
                            blade);
                } elseif ($entry->status === 'pre_booked') {
                    $stat = Blade::render(<<<blade
                                <x-badge fuchsia label="Pre-booked" />
                            blade);
                } else {
                    $stat = Blade::render(<<<blade
                                <x-badge negative label="Booked" />
                            blade);
                }

                return $stat;
                
            })
            ->add('name_lower', fn (Asset $model) => strtolower(e($model->name)))
            ->add('created_at')
            ->add('created_at_formatted', fn (Asset $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'index'),

            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Type', 'type')
                ->searchable(),

            Column::make('Location', 'location')
                ->searchable(),

            Column::make('Status', 'status_formatted'),

            Column::make('Created at', 'created_at_formatted'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::inputText('name'),
            // Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    // #[\Livewire\Attributes\On('edit')]
    // public function edit($rowId): void
    // {
    //     // $this->js('alert('.$rowId.')');

    //     $this->js(
    //         '$openModal("createAssetModal")'
    //     );
    // }

    public function actions(Asset $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                // ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->class('bg-blue-700 px-3 py-1 rounded-lg hover:bg-blue-500')
                ->dispatch('edit-asset', ['assetId' => $row->id]),
            Button::add('delete')
                ->confirm('Are you sure you want to delete this asset?')
                ->slot('Delete')
                ->id()
                ->class('bg-red-800 rounded-md px-2 py-1 hover:bg-red-600 disabled:cursor-not-allowed')
                ->dispatch('delete-asset', ['assetId' => $row->id]),
        ];
    }

    /*
    public function actionRules(Asset $row): array
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
