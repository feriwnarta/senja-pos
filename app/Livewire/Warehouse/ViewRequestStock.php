<?php

namespace App\Livewire\Warehouse;

use App\Models\RequestStock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Mockery\Exception;

class ViewRequestStock extends Component
{

    public RequestStock $requestStock;
    public string $requestStockId;
    public bool $isEdited = false;
    public array $requestStockDetail = [];

    public Collection $items;

    public string $selectedId = '';

    public function mount(RequestStock $requestStock)
    {
        $this->requestStock = $requestStock;
        $this->requestStockId = $requestStock->id;

        $this->getRequestStockItems();
    }

    public function getRequestStockItems()
    {
        if (is_null($this->requestStock)) {
            notify()->error('Ada sesuatu yang salah');
            return;
        }

        try {
            $requestStockDetail = $this->requestStock->requestStockDetail->map(function ($request) {
                return [
                    'request_stock_detail_id' => $request->id,
                    'items' => [
                        'id' => $request->item->id,
                        'name' => $request->item->name,
                        'unit' => $request->item->unit->name,
                    ],
                    'qty_request' => $request->qty,
                    'qty_accept' => $request->qty_accept
                ];
            })->toArray();

            $this->requestStockDetail = $requestStockDetail;
        } catch (Exception $exception) {
            notify()->error('Ada sesuatu yang salah');
            Log::error('gagal mengambil request stock detail saat user mengedit request stock');
            report($exception);
        }
    }

    public function addSelectedItem()
    {

        $this->requestStockDetail[] = [
            'request_stock_detail_id' => '',
            'items' => [
                'id' => '',
                'name' => '',
                'unit' => '',
            ],
            'qty_request' => '',
            'qty_accept' => '',
        ];

    }

    public function loadData()
    {
        if (is_null($this->requestStock)) {
            Log::error('gagal mendapatkan data items saat ingin menambah item di edit request');
            return;
        }

        $this->items = ($this->requestStock->warehouse->warehouseItem()->with('items', 'items.unit')->get());

    }

    public function selectedItem($index)
    {

        if (!isset($this->selectedId)) {
            return;
        }

        $item = $this->items->firstWhere('items.id', $this->selectedId);

        $this->requestStockDetail[$index] = [
            'request_stock_detail_id' => '',
            'items' => [
                'id' => $this->selectedId,
                'name' => $item->items->name,
                'unit' => $item->items->unit->name,
            ],
            'qty_request' => '',
            'qty_accept' => '0.00',
        ];

    }

    public function render()
    {
        return view('livewire.warehouse.view-request-stock');
    }

    /**
     * hapus baris daftar item permintaan stock
     *
     * @param $index
     * @return void
     */
    public function delete($index)
    {
        unset($this->requestStockDetail[$index]);
    }

    public function editRequestStock()
    {
        $this->isEdited = true;
    }


}
