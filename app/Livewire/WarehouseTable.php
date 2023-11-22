<?php

namespace App\Livewire;

use App\Models\Warehouse;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
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
            ->join('areas', 'areas.warehouses_id', '=', 'warehouses.id')
            ->join('racks', 'racks.areas_id', '=', 'areas.id')
            ->select([
                'warehouses.id',
                'warehouses.address',
                'warehouses.name',
                'warehouses.warehouse_code',
                DB::raw('GROUP_CONCAT(areas.name) as areas_names'),
                DB::raw('GROUP_CONCAT(racks.name) as rack_names'),
            ])
            ->groupBy('warehouses.id', 'warehouses.address', 'warehouses.name', 'warehouses.warehouse_code');
    }


    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()->addColumn('area_formatted', function (Warehouse $warehouse) {
            return $warehouse->areas->pluck('name')->implode(', ');
        })->addColumn('areas_names')->addColumn('rack_names')->addColumn('racks_formatted', function (Warehouse $warehouse) {

            Log::debug($warehouse);
            // Menggunakan flatMap untuk mendapatkan semua racks dari semua areas
            $allRacks = $warehouse->areas->flatMap(function ($area) {
                return $area->racks->pluck('name');
            })->unique();


            return $allRacks->implode(', ');
        });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Kode gudang')->field('warehouse_code', 'warehouse_code')->searchable()->sortable(),
            Column::add()->title('Nama gudang')->field('name')->searchable()->sortable(),
            Column::add()->title('Area')->field('area_formatted', 'areas.name')->searchable()->sortable(),
            Column::add()->title('Rak')->field('racks_formatted', 'racks.name')->searchable()->sortable(),
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

    #[On('detail')]
    public function edit($id): void
    {
        $this->redirect("/warehouse/list-warehouse/detail-warehouse?q=$id", true);
    }

    public function actions(Warehouse $row): array
    {
        return [
            Button::add('edit')
                ->slot('Detail')
                ->id()
                ->class('btn btn-text-only-primary')->dispatch('detail', ['id' => $row->id])

        ];
    }

}
