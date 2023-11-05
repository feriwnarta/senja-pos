<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use App\Service\WarehouseService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class DetailWarehousePage extends Component
{
    #[Url(as: 'q')]
    public string $urlQuery = '';
    public string $warehouseId;

    public string $locationWarehouse;
    public array $areas = [];
    public bool $isAddedArea = false;
    public Warehouse $warehouse;

    public string $mode = 'view';
    public string $htmlCondition;
    public array $itemData;
    public ?string $nextCursor = null;

    public bool $isShow = false;

    public string $warehouseCode;
    #[Rule('required|min:5|unique:warehouses,name')]
    public string $warehouseName;
    public array $itemEditData;
    public array $itemSelected = ['dataItem' => []];
    private WarehouseService $warehouseService;

    public function mount()
    {

        // TODO: extract url query, untuk menentukan mode edit atau view


        // extract url query
        $this->extractUrl();


        $this->warehouseService = app()->make(WarehouseService::class);
        $this->getDetailDataWarehouse($this->warehouseId);
    }

    /**
     * fungsi ini dilakukan untuk melakukan extract url query parameter
     * ini digunakan untuk menentukan apakah warehouse dalam mode edit atau view
     * saat web direfresh tampilan akan menyesuaikan berdasarkan mode
     * @return void
     */
    private function extractUrl()
    {
        // jika url kosong atau null, maka redirect ke warehouse list
        if ($this->urlQuery == '' || $this->urlQuery == null) {
            $this->redirect('/warehouse/list-warehouse/', true);
        }


        // Mencari nilai parameter "mode" menggunakan preg_match
        if (preg_match('/^([^&]+)&mode=([^&]+)/', $this->urlQuery, $matches)) {
            $id = $matches[1];
            $modeValue = $matches[2];

            // set id
            $this->warehouseId = $id;

            // ubah modenya menjadi edit
            if ($modeValue == 'edit') {
                $this->dispatch('edit-warehouse');
            }

        } else {
            $this->warehouseId = $this->urlQuery;
        }

    }

    /**
     * dapatkan data detail gudang termasuk area, rack dan item
     * @param string $id
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getDetailDataWarehouse(string $id)
    {


        // jika id nya kosong
        if (empty($id)) {
            return;
        }

        try {
            $this->warehouse = $this->warehouseService->getWarehouseById($id);

            // tampilkan warehouse tidak ketemu
            if ($this->warehouse == null) return;

            $this->areas = $this->warehouseService->getDetailDataAreaRackItemWarehouse($this->warehouse);

        } catch (\Exception $e) {
            // warehouse not found
            if ($e->getCode() == 1 || $e->getCode() == 2) {
                $this->htmlCondition = 'Data gudang tidak ditemukan, pastikan gudang ada jika masalah masih berlanjut silahkan hubungi administrator';
            }
        }


    }

    #[On('update-item-rack')]
    public function updateItemRack($rackId, $item)
    {
        Log::info('update item');

        foreach ($item as $singleItem) {
            $items[] = [
                'name' => $singleItem
            ];
        }

        Log::debug($items);
        
        foreach ($this->areas as $areaKey => $dataArea) {
            foreach ($dataArea as $area) {
                foreach ($area['racks'] as $rackKey => $rack) {
                    if ($rack['id'] == $rackId) {
                        $this->areas[$areaKey]['area']['racks'][$rackKey]['item'] = $items;
                    }
                }
            }
        }
    }

    /**
     * listener dapatkan isi item rack berdasarkan id
     * fungsi ini akan memanggil fungsi warehouse service getItemRackByIdWithCursor
     * kembalian dari fungsi ini adalah cursor array
     * perhatikan bahwa pengelolaan next cursor harus kritikal
     * @return void
     */

    #[On('detail-item-rack')]
    public function getItemByRackId(string $id)
    {
        // kosongkan array itemData
        $this->itemData = [];

        // buat is show true agar modal tidak tertutup
        $this->isShow = true;

        // dapatkan data item berdasarkan rack dengan memanggil fungsi get item rack by id
        // dari warehouse service
        $this->warehouseService = app()->make(WarehouseService::class);
        $cursor = $this->warehouseService->getItemRackByIdWithCursor($id);

        // jika isinya null dapat dipastikan bahwa rak tidak memiliki item
        if ($cursor == null) {
            // cursor null dipastikan item tidak ada didalam rack ini
            return;
        }

        // cursor ditemukan olah cursor data ini
        $this->itemData = $cursor['data'];


        // simpan next id cursor untuk mencari data berikut nya
        $this->nextCursor = $cursor['next_cursor'];

        // kirim event ke javascript file detail warehouse untuk menjalankan login modal terbuka dan discroll
        $this->dispatch('after-load-modal-detail-item', rackId: $id);

    }

    #[On('detail-item-rack-edit')]
    public function getItemEditAdded($id)
    {
        $this->warehouseService = app()->make(WarehouseService::class);
        $this->itemEditData = $this->warehouseService->getItemRackAddedByIdWithCursor($id)['data'];


        // lakukan pengecekan apakah item sudah ter - edit
        if (!empty($this->itemSelected['dataItem'])) {
            foreach ($this->itemSelected['dataItem'] as $dataItem) {
                // rak yang sama
                if ($dataItem['rack_id'] == $id) {
                    foreach ($dataItem['item'] as $item) {

                        foreach ($this->itemEditData as $itemEditKey => $itemEdit) {
                            if ($itemEdit['id'] == $item['id']) {
                                $this->itemEditData[$itemEditKey]['checked'] = $item['checked'];
                                break;
                            }
                        }

                    }
                }
            }


        }


        $this->dispatch('after-load-modal-edit-item', rackId: $id);
    }

    #[On('item-added')]
    public function itemAdded($rackId, $id, $value)
    {
        Log::info('load');
        Log::info($rackId);
        Log::info($id);
        Log::info($value);

        $rackFound = false;

        foreach ($this->itemSelected['dataItem'] as $dataItemKey => $dataItem) {
            if (isset($dataItem['rack_id']) && $dataItem['rack_id'] == $rackId) {
                $rackFound = true;

                if (isset($dataItem['item'])) {
                    $itemFound = false;

                    foreach ($dataItem['item'] as $itemKey => $item) {
                        if ($item['id'] == $id) {
                            Log::error('sama');
                            $this->itemSelected['dataItem'][$dataItemKey]['item'][$itemKey]['checked'] = $value;
                            $itemFound = true;
                            break;
                        }
                    }

                    if (!$itemFound) {
                        $this->itemSelected['dataItem'][$dataItemKey]['item'][] = [
                            'id' => $id,
                            'checked' => $value,
                        ];
                    }
                }

                // Keluar dari loop jika rack_id sudah ditemukan
                break;
            }
        }

        if (!$rackFound) {
            $this->itemSelected['dataItem'][] = [
                'rack_id' => $rackId,
                'item' => [
                    [
                        'id' => $id,
                        'checked' => $value,
                    ]
                ]
            ];
        }
    }

    #[On('load-more')]
    public function loadMore($rackId)
    {

        Log::info($this->nextCursor);

        if ($this->nextCursor != null) {
            $this->warehouseService = app()->make(WarehouseService::class);
            $nextCursor = $this->warehouseService->nextCursorItemRack($rackId, $this->nextCursor);
            Log::info('load more');
            Log::info($nextCursor);

            if ($nextCursor['data'] != null) {
                foreach ($nextCursor['data'] as $data) {
                    $this->itemData[] = $data;
                }
            }

            $this->nextCursor = $nextCursor['next_cursor'];
        }
    }

    public function selectItem($id, $name, $checked)
    {

    }

    #[On('edit-warehouse')]
    public function editWarehouse()
    {
        // buat mode nya menjadi edit
        $this->mode = 'edit';
        $this->urlQuery = "{$this->warehouseId}&mode=edit";

        if ($this->warehouse != null) {
            // isi field warehouse name
            $this->warehouseName = $this->warehouse->name;
        }


    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="d-flex justify-content-center align-items-center position-absolute top-50 start-50">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.warehouse.detail-warehouse-page');
    }

    public function rendered($view, $html)
    {
        $this->dispatch('set-width-title');
        $this->dispatch('update-menu');
    }


}
