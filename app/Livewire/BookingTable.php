<?php

namespace App\Livewire;

use App\Models\Booking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class BookingTable extends PowerGridComponent
{
    public string $tableName = 'bookingTable';

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
        return Booking::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('client_id')
            ->add('client_id_formatted', fn (Booking $model) => $model->client->name)
            ->add('asset_id')
            ->add('asset_id_formatted', fn (Booking $model) => $model->asset->name)
            ->add('start_date_formatted', fn (Booking $model) => Carbon::parse($model->start_date)->format('d/m/Y'))
            ->add('end_date_formatted', fn (Booking $model) => Carbon::parse($model->end_date)->format('d/m/Y'))
            ->add('total_price')
            ->add('status')
            ->add('status_formatted', function ($entry) {
                if ($entry->status === 'pending') {
                    $stat = Blade::render(<<<blade
                                <x-badge amber label="Pending" />
                            blade);
                } elseif ($entry->status === 'confirmed') {
                    $stat = Blade::render(<<<blade
                                <x-badge fuchsia label="Confirmed" />
                            blade);
                } elseif ($entry->status === 'completed') {
                    $stat = Blade::render(<<<blade
                                <x-badge emerald label="Completed" />
                            blade);
                } else {
                    $stat = Blade::render(<<<blade
                                <x-badge negative label="Cancelled" />
                            blade);
                }

                return $stat;
                
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Client', 'client_id_formatted', 'client_id'),
            Column::make('Asset', 'asset_id_formatted', 'asset_id'),
            Column::make('Start date', 'start_date_formatted', 'start_date')
                ->sortable(),

            Column::make('End date', 'end_date_formatted', 'end_date')
                ->sortable(),

            Column::make('Total price', 'total_price')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status_formatted' ,'status')
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
            Filter::datepicker('start_date'),
            Filter::datepicker('end_date'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Booking $row): array
    {
        return [
            Button::add('details')
                ->slot('Details')
                ->id()
                ->class('bg-white text-slate-900 px-3 py-1 rounded-lg hover:bg-slate-900 hover:text-white')
                ->route('clients.bookings.details', ['id' => $row->id]),
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('bg-blue-700 px-3 py-1 rounded-lg hover:bg-blue-500')
                ->dispatch('edit-booking', ['bookingId' => $row->id]),
            Button::add('delete')
                ->confirm('Are you sure you want to delete this booking?')
                ->slot('Delete')
                ->id()
                ->class('bg-red-800 rounded-md px-2 py-1 hover:bg-red-600 disabled:cursor-not-allowed')
                ->dispatch('delete-booking', ['bookingId' => $row->id]),
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
