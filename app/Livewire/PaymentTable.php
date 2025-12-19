<?php

namespace App\Livewire;

use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class PaymentTable extends PowerGridComponent
{
    public string $tableName = 'paymentTable';
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
        return Payment::query();
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
            ->add('index', function () {
                return ++$this->rowNumber;
            })
            // ->add('booking_id')
            ->add('method')
            ->add('method_formatted', fn (Payment $model) => ucfirst($model->method))
            ->add('amount')
            ->add('status')
            ->add('status_formatted', fn (Payment $model) => ucfirst($model->status))
            ->add('description')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'index'),
            Column::make('Method', 'method_formatted', 'method')
                ->sortable()
                ->searchable(),

            Column::make('Amount', 'amount'),
            Column::make('Status', 'status_formatted', 'status'),
            Column::make('Description', 'description'),
            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            // Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    // public function actions(Payment $row): array
    // {
    //     return [
    //         Button::add('edit')
    //             ->slot('Edit: '.$row->id)
    //             ->id()
    //             ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
    //             ->dispatch('edit', ['rowId' => $row->id])
    //     ];
    // }

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
