<?php

namespace App\Livewire\Warehouse;

use App\Models\Item;
use App\Models\Warehouse;
use App\Service\WarehouseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Ramsey\Uuid\Uuid;

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
        $result = $this->warehouseService->getItemRackAddedByIdWithCursor($id);
        $this->nextCursorEdit = $result['next_cursor'];
        $this->itemEditData = $result['data'];


        Log::info($this->itemSelected);

        // lakukan pengecekan apakah item sudah ter - edit
        // Jika data item terpilih tidak kosong
        if (!empty($this->itemSelected['dataItem'])) {
            // Iterasi melalui setiap data item
            foreach ($this->itemSelected['dataItem'] as $dataItem) {
                // Jika rack ID sama dengan ID yang diberikan
                if ($dataItem['rack_id'] == $id) {
                    // Iterasi melalui setiap item di dalam data item
                    foreach ($dataItem['item'] as $item) {
                        // Iterasi melalui setiap item dalam data edit
                        foreach ($this->itemEditData as $itemEditKey => $itemEdit) {
                            // Jika ID item di dalam data edit sama dengan ID item di dalam data item
                            if ($itemEdit['id'] == $item['id']) {
                                // Tetapkan nilai 'checked' dari item edit ke nilai 'checked' dari item di dalam data item
                                $this->itemEditData[$itemEditKey]['checked'] = $item['checked'];
                                break;
                            }
                        }
                    }

                } else {
                    foreach ($dataItem['item'] as $item) {
                        $isItemEdited = false; // Tambahkan variabel penanda untuk item yang sudah di-edit
                        // Iterasi melalui setiap item dalam data edit
                        foreach ($this->itemEditData as $itemEditKey => $itemEdit) {
                            // Jika ID item di dalam data edit sama dengan ID item di dalam data item
                            if ($itemEdit['id'] == $item['id'] && $item['checked'] == 'true') {
                                // Tetapkan nilai 'checked' dari item edit ke nilai 'checked' dari item di dalam data item
                                $this->itemEditData[$itemEditKey]['disabled'] = 'true';
                                $isItemEdited = true;
                                break;
                            }
                        }

                        // Jika item belum di-edit, tambahkan ke dalam data edit
                        if (!$isItemEdited && $item['checked'] == 'false') {
                            $foundItem = Item::find($item['id']);
                            $this->itemEditData[] = [
                                'id' => $item['id'],
                                'name' => $foundItem['name'],
                                'checked' => 'false',
                            ];
                        }
                    }
                }

            }
            Log::info($this->itemEditData);
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

    #[On('load-more-edit')]
    public function loadMoreEdit($rackId)
    {

        if ($this->nextCursorEdit != null) {
            $this->warehouseService = app()->make(WarehouseService::class);
            $nextCursor = $this->warehouseService->nextCursorItemRackAddedById($rackId, $this->nextCursorEdit);
            $this->dispatch('stop-request-edit');

            if ($nextCursor['data'] != null) {
                // Filter duplikat menggunakan ID
                $existingIds = array_column($this->itemEditData, 'id');

                foreach ($nextCursor['data'] as $data) {
                    // Periksa apakah ID sudah ada di $this->itemEditData
                    if (!in_array($data['id'], $existingIds)) {
                        $this->itemEditData[] = [
                            'id' => $data['id'],
                            'name' => $data['name'],
                            'checked' => 'false',
                        ];
                    }
                }


            }

            Log::info($this->itemEditData);

            $this->nextCursor = $nextCursor['next_cursor'];
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
        DB::beginTransaction();
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

    public function addNewArea()
    {
        Log::debug(json_encode($this->areas, JSON_PRETTY_PRINT));

        $rackId = Uuid::uuid4();
        $areaId = Uuid::uuid4();

        $this->areas[] = [
            'area' => [
                'id' => $areaId->toString(),
                'area' => '',
                'racks' => [
                    [
                        'id' => $rackId->toString(),
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
        $rackId = Uuid::uuid4();

        $this->areas[count($this->areas) - 1]['area']['racks'][] = ['id' => $rackId->toString(),
            'name' => '',
            'category_inventory' => '',
            'item' => [],];
    }

    #[On('add-new-item-rack-edit')]
    public function loadModalNewIte($id)
    {
        $this->itemEditData = [];
        $this->warehouseService = app()->make(WarehouseService::class);
        $result = $this->warehouseService->getItemNotYetAddedRackCursor();


        if (!empty($result)) {

            // lakukan pengecekan item selected
            Log::info('item selected');
            Log::info($this->itemSelected);

            $addedItemIds = [];

            foreach ($result['data'] as $dataKey => $dataItem) {
                foreach ($this->itemSelected['dataItem'] as $itemSelected) {
                    foreach ($itemSelected['item'] as $item) {
                        if ($item['checked'] == 'false' && !in_array($item['id'], $addedItemIds)) {
                            // cari data item berdasarkan id
                            $foundItem = Item::find($item['id']);
                            if ($foundItem != null) {
                                $result['data'][] = [
                                    'id' => $foundItem['id'],
                                    'name' => $foundItem['name'],
                                    'checked' => $item['checked'],
                                ];
                                $addedItemIds[] = $foundItem['id']; // Tandai ID item sebagai ditambahkan
                            }
                        }
                    }
                }
                // Setel nilai 'checked' untuk data yang sudah ada
                $result['data'][$dataKey]['checked'] = 'false';
            }


            Log::info('cursor data');
            Log::info($result['data']);
            $this->itemEditData = $result['data'];
        }

        $this->dispatch('after-load-modal-edit-item', rackId: $id);
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
