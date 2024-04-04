<?php

namespace App\Livewire\Warehouse\Components\Paginator;


use App\Models\WarehouseItem;
use Livewire\Component;
use Livewire\WithPagination;

class PaginatorWarehouseItem extends Component
{
    use WithPagination;

    public string $warehouseId;

    public function render()
    {
        return view('livewire.warehouse.components.paginator.paginator-warehouse-item', [
                'warehouseItems' => WarehouseItem::where('warehouses_id', $this->warehouseId)->with('items', 'items.unit')->paginate(10)
        ]);
    }
}
