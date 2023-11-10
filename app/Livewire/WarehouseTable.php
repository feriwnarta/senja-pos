<?php

namespace App\Livewire;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Modelable;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class WarehouseTable extends PowerGridComponent
{
    use WithExport;

    public bool $deferLoading = true; // default false

    #[Modelable]
    public string $search = '';

    public string $loadingComponent = 'components.loading';


    public function setUp(): array
    {


        return [

            Exportable::make('warehouse-data')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showToggleColumns(),
            Footer::make()
                ->showPerPage(7)
                ->showRecordCount(),


        ];

    }

    public function datasource(): Builder
    {
        return Warehouse::query()
            ->join('areas', function ($areas) {
                $areas->on('areas.warehouses_id', '=', 'warehouses.id');
            })->join('racks', function ($racks) {
                $racks->on('racks.areas_id', '=', 'areas.id');
            })
            ->select(['warehouses.id', 'warehouses.address', 'warehouses.name', 'warehouses.warehouse_code', 'areas.name AS areas_name', 'racks.name AS rack_name']);
    }


    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
//        return PowerGrid::columns()
//            ->addColumn('id')
//            /** Example of custom column using a closure **/
//            ->addColumn('id_lower', fn(Warehouse $model) => strtolower(e($model->id)))
//            ->addColumn('created_at_formatted', fn(Warehouse $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
        return PowerGrid::columns();
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Kode gudang')->field('warehouse_code', 'warehouse_code')->searchable()->sortable(),
            Column::add()->title('Nama gudang')->field('name')->searchable()->sortable(),
            Column::add()->title('Area')->field('areas_name')->searchable()->sortable(),
            Column::add()->title('Rak')->field('rack_name')->searchable()->sortable(),
            Column::add()->title('Alamat')->field('address')->searchable()->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::datetimepicker('created_at'),
        ];
    }

    #[\Livewire\Attributes\On('detail')]
    public function edit($id): void
    {
        $this->redirect("/warehouse/list-warehouse/detail-warehouse?q=$id", true);
    }

    public function actions(\App\Models\Warehouse $row): array
    {
        return [
            Button::add('edit')
                ->slot('Detail')
                ->id()
                ->class('btn btn-text-only-primary')->dispatch('detail', ['id' => $row->id])

        ];
    }

}
