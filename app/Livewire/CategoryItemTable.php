<?php

namespace App\Livewire;

use App\Models\Category;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class CategoryItemTable extends PowerGridComponent
{
    use WithExport;

    public bool $deferLoading = true; // default false

    public string $loadingComponent = 'components.loading';

    #[Modelable]
    public string $search = '';

    public function setUp(): array
    {

        return [
            Exportable::make('category_item_data')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showToggleColumns(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Category::query()
            ->join('categories_items', 'categories.id', '=', 'categories_items.categories_id')
            ->join('items', 'categories_items.items_id', '=', 'items.id')
            ->select(['categories.id', 'categories.name', 'categories.code', DB::raw('GROUP_CONCAT(items.name) as item_names')])
            ->groupBy('categories.id', 'categories.name', 'categories.code');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()->addColumn('item_formatted', function (Category $category) {
            Log::debug($category);
            return $category->items->pluck('name')->implode(', ');
        });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Kode kategori')->field('code', 'code')->searchable()->sortable(),
            Column::add()->title('Nama kategori')->field('name')->searchable()->sortable(),
            Column::add()->title('Item')->field('item_formatted', 'item_name')->searchable()->sortable(),
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [

        ];
    }

    #[On('detail')]
    public function detail($id): void
    {
        $this->redirect('/warehouse/category-item/detail-category', true);
    }

    public function actions(Category $category): array
    {
        return [
            Button::add('edit')
                ->slot('Detail')
                ->id()
                ->class('btn btn-text-only-primary')->dispatch('detail', ['id' => $category->id])
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
