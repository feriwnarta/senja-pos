<?php

namespace App\Livewire;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Modelable;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class UnitTable extends PowerGridComponent
{
    use WithExport;

    public bool $deferLoading = true; // default false

    public string $loadingComponent = 'components.loading';

    #[Modelable]
    public string $search;

    public function setUp(): array
    {

        return [
            Exportable::make('unit-data')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage(7)
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Unit::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('created_at_formatted', fn(Unit $model) => Carbon::createFromFormat('Y-m-d H:i:s', $model->created_at)->locale('id_ID')->isoFormat('D MMMM Y'));
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Kode unit')->field('code', 'code')->searchable()->sortable(),
            Column::add()->title('Unit')->field('name')->searchable()->sortable(),
            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [

        ];
    }

//    #[On('detail')]
//    public function detail($id): void
//    {
//        $this->redirect("/warehouse/unit/detail-unit?q=$id", true);
//    }

//    public function actions(Unit $row): array
//    {
//        return [
//            Button::add('edit')
//                ->slot('Detail')
//                ->id()
//                ->class('btn btn-text-only-primary')->dispatch('detail', ['id' => $row->id])
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
