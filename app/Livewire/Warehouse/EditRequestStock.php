<?php

namespace App\Livewire\Warehouse;

use App\Models\RequestStock;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EditRequestStock extends Component
{

    public RequestStock $requestStock;
    public string $note;
    public string $requestStockId;
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

        // load note
        $this->note = $this->requestStock->note != null ? $this->requestStock->note : '';

        try {
            $requestStockDetail = $this->requestStock->requestStockDetail->map(function ($request) {
                return [
                    'request_stock_detail_id' => $request->id,
                    'items' => [
                        'id' => $request->item->id,
                        'name' => $request->item->name,
                        'unit' => $request->item->unit->name,
                    ],
                    'qty_request' => number_format($request->qty),
                    'qty_accept' => number_format($request->qty_accept)
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

    public function saveEdit()
    {
        $this->validate([
            'requestStockDetail.*.items.id' => 'required|min:16',
            'requestStockDetail.*.qty_request' => 'required|numeric|min:1'
        ], [
                'requestStockDetail.*.items.id' => 'Item must be required',
            ]
        );

        $this->processSave();

    }

    public function processSave()
    {
        try {
            Log::info('Proses simpan edit request stok');
            // hapus semua reques detail
            DB::transaction(function () {
                $this->requestStock->requestStockDetail()->delete();

                foreach ($this->requestStockDetail as $requestStock) {
                    $this->requestStock->requestStockDetail()->create([
                        'items_id' => $requestStock['items']['id'],
                        'qty' => $requestStock['qty_request'],
                        'qty_accept' => str_replace('.', '', $requestStock['qty_accept']),
                    ]);
                }

                $this->requestStock->note = $this->note == '' ? null : $this->note;
                $this->requestStock->save();
            });
            $this->redirect("/warehouse/transaction/request-stock/view/{$this->requestStockId}", true);
            notify()->success('Berhasil simpan ');
        } catch (Exception $exception) {
            notify()->error("Gagal");
            Log::error('gagal menyimpan edit request stock');
            report($exception);
        }

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

    public function render()
    {
        return view('livewire.warehouse.edit-request-stock');
    }
}
