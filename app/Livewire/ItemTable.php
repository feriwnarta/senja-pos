<?php

namespace App\Livewire;

use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ItemTable extends PowerGridComponent
{
    use WithExport;

    #[Reactive]
    public ?string $selected = 'asdasd';

    public function setUp(): array
    {

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        Log::info($this->selected);
        return Item::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function handleTypeItemChange()
    {

    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            /** Example of custom column using a closure **/
            ->addColumn('id_lower', fn(Item $model) => strtolower(e($model->id)))
            ->addColumn('created_at_formatted', fn(Item $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            ->addColumn('unit_formatted', fn(Item $model) => $model->unit->name);
    }

    public function columns(): array
    {
        return [
            Column::make('Kode', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Nama', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Unit', 'unit_formatted', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Stok aktual', 'actual_stock_formatted'),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

        ];
    }

    #[On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

//    public function filters(): array
//    {
//        return [
//            Filter::inputText('id')->operators(['contains']),
//            Filter::datetimepicker('created_at'),
//        ];
//    }

    private function findItemOrOutlet(string $id): Builder
    {
        Item::query()->join('items_central_kitchens', 'items.id', '=', 'items_central_kitchens.id');
    }

//    public function actions(Item $row): array
//    {
//        return [
//            Button::add('edit')
//                ->slot('Edit: ' . $row->id)
//                ->id()
//                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
//                ->dispatch('edit', ['rowId' => $row->id])
//        ];
//    }

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
