<?php

namespace App\Livewire\Warehouse;

use App\Models\Item;
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
    public ?string $nextCursorEdit = null;

    public bool $isShow = false;

    public string $rackId;

    public string $warehouseCode;
    #[Rule('required|min:5|unique:warehouses,address')]
    public string $warehouseAddress;
    #[Rule('required|min:5|unique:warehouses,name')]
    public string $warehouseName;
    public array $itemEditData;
    public array $itemSelected = ['dataItem' => []];
    private WarehouseService $warehouseService;

    #[On('update-item-rack')]
    public function updateItemRack($rackId, $item)
    {
        Log::info('update item');

        $items = [];

        foreach ($item as $singleItem) {
            $items[] = [
                'name' => $singleItem
            ];
        }


        Log::debug($this->areas);
        Log::debug($items);

        foreach ($this->areas as $areaKey => $dataArea) {
            foreach ($dataArea as $area) {
                foreach ($area['racks'] as $rackKey => $rack) {
                    if ($rack['id'] == $rackId) {
                        // Membersihkan item sebelum menambahkan yang baru
                        $this->areas[$areaKey]['area']['racks'][$rackKey]['item'] = [];
                        // Menambahkan item yang baru
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

        $this->itemEditData = [];
        $this->warehouseService = app()->make(WarehouseService::class);
        $result = $this->warehouseService->getItemRackAddedByIdWithCursor($id);
        $this->nextCursorEdit = $result['next_cursor'];


        $this->setItemEditData($result['data'], $id);


        $this->dispatch('after-load-modal-edit-item', rackId: $id);
    }

    /**
     * @param $data
     * @param $rackId
     * @return void
     */
    private function setItemEditData($data, $rackId): void
    {
        foreach ($data as $item) {

            if ($item['racks_id'] == $rackId) {
                $this->itemEditData[] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'checked' => true,
                ];
                continue;
            }
            $this->itemEditData[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'checked' => false,
            ];

        }
    }

    #[On('item-added')]
    public function itemAdded($rackId, $id, $value)
    {
        Log::info('load');
        Log::info($rackId);
        Log::info($id);
        Log::info($value);

        $this->addRackToItem($rackId, $id, $value);

    }

    private function addRackToItem($rackId, $itemId, $status): bool
    {

        try {
            $item = Item::findOrFail($itemId);
            $item->racks_id = ($status) ? $rackId : null;
            $itemSaved = $item->save();

            if ($itemSaved) {
                return true;
            } else {
                // Jika save() gagal
                Log::error('Failed to save item');
                return false;
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika item tidak ditemukan
            Log::error('Item not found: ' . $itemId);
            return false;
        } catch (\Exception $e) {
            // Jika terjadi exception lainnya
            Log::error($e);
            return false;
        }

    }

    #[On('load-more-edit')]
    public function loadMoreEdit($rackId)
    {
        if ($this->nextCursorEdit != null) {
            $this->warehouseService = app()->make(WarehouseService::class);
            $result = $this->warehouseService->nextCursorItemRackAddedById($rackId, $this->nextCursorEdit);
            $this->dispatch('stop-request-edit');

            Log::info('load more');
            Log::info($result);
            Log::info($this->nextCursorEdit);
            Log::info($result);

            $this->setItemEditData($result['data'], $rackId);
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

    #[On('edit-warehouse')]
    public function editWarehouse()
    {

        // buat mode nya menjadi edit
        $this->mode = 'edit';
        $this->urlQuery = "{$this->warehouseId}&mode=edit";

        if (isset($this->warehouse)) {
            if ($this->warehouse != null) {
                // isi field warehouse name
                $this->warehouseName = $this->warehouse->name;
            }
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

    public function addNewArea()
    {
        Log::debug(json_encode($this->areas, JSON_PRETTY_PRINT));
        $this->warehouseService = app()->make(WarehouseService::class);
        $area = $this->warehouseService->addNewArea($this->warehouseId);


        if ($area == null) {
            return;
        }

        $rack = $this->warehouseService->addNewRack($area->id);

        if ($rack == null) {

        }

        $this->areas[] = [
            'area' => [
                'id' => $area->id,
                'area' => '',
                'racks' => [
                    [
                        'id' => $rack->id,
                        'name' => '',
                        'category_inventory' => '',
                        'item' => []
                    ]
                ]
            ]
        ];
    }

    public function addNewRack()
    {

        $areaId = $this->areas[count($this->areas) - 1]['area']['id'];
        $this->warehouseService = app()->make(WarehouseService::class);
        $rack = $this->warehouseService->addNewRack($areaId);

        if ($rack == null) {
            // error saat membuat rack baru
            return;
        }

        $this->areas[count($this->areas) - 1]['area']['racks'][] = ['id' => $rack->id,
            'name' => '',
            'category_inventory' => '',
            'item' => [],];
    }

    public function removeArea($areaIndex)
    {
        if (!empty($this->areas)) {

            if ($areaIndex == 0) {
                $this->js("alert('gudang wajib memiliki 1 area, area ini tidak akan terhapus')");
                return;
            }

            unset($this->areas[$areaIndex]);
        }

    }


    public function removeAreaRack($areaIndex, $rackIndex)
    {

        Log::info('remove area rack function');
        Log::info($this->areas);

        if (!empty($this->areas)) {
            unset($this->areas[$areaIndex]['area']['racks'][$rackIndex]);
        }

    }

    #[On('saveEditWarehouse')]
    public function validateInput()
    {
        Log::info(json_encode($this->areas, JSON_PRETTY_PRINT));

        /**
         * Lakukan validasi untuk array areas
         * pastikan bahwa nama rak untuk setiap area unique
         */
        $this->validate([
            'areas.*.area.area' => [
                'required',
                'min:2',
                'distinct',
            ],
            'areas.*.area.racks.0.name' => 'required|min:2',
            'areas.*.area.racks.0.category_inventory' => 'required|min:2',
            'areas.*.area.racks.*.name' => [
                'required',
                'min:2',
                function ($attribute, $value, $fail) {

                    // ambil index dari validasi laravel
                    $parts = explode('.', $attribute);
                    $keyPosition = array_search('areas', $parts);

                    if ($keyPosition !== false && isset($parts[$keyPosition + 1])) {
                        $desiredValue = $parts[$keyPosition + 1];

                        $count = 0;

                        // lakukan proses pencarian rack yang duplikat untuk satu area
                        foreach ($this->areas[$desiredValue] as $area) {
                            Log::debug($area);
                            if (isset($area['racks'])) {
                                foreach ($area['racks'] as $rack) {
                                    if ($value == $rack['name']) {
                                        $count++;
                                    }
                                }
                            }
                        }

                        // deteksi duplikat data
                        if ($count > 1) {
                            $fail("The $attribute must contain distinct values.");
                        }

                    }

                    Log::info($attribute);
                    Log::info($value);
                }
            ],
            'areas.*.area.racks.*.category_inventory' => 'required|min:2',
        ]);

        // TODO: Lakukan proses penyimpanan data warehouse kedatabase
        Log::info('proses');
        $this->saveWarehouse($this->areas, $this->warehouseId);

    }

    private function saveWarehouse(array $areas, string $warehouseId)
    {
        Log::info('proses simpan edit warehouse');
        Log::info(json_encode($this->areas, JSON_PRETTY_PRINT));

        try {
            $this->warehouseService = app()->make(WarehouseService::class);
            $resultSave = $this->warehouseService->saveWarehouse($areas, $warehouseId);

            if ($resultSave) {
                $this->js("alert('berhasil edit gudang')");
                $this->redirect("/warehouse/list-warehouse/detail-warehouse?q={$this->warehouseId}", true);
                $this->reset();

            }

        } catch (\Exception $e) {
            $this->js("alert('ada kesalahan')");
            Log::error($e->getMessage());
        }
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

            // set addess
            $this->warehouseAddress = $this->warehouse->address;

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


}
