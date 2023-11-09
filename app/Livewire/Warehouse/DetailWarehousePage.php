<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use App\Service\WarehouseService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class DetailWarehousePage extends Component
{
    #[Url(as: 'q')]
    public string $urlQuery;

    public string $locationWarehouse;
    public array $areas = [];
    public bool $isAddedArea = false;
    public Warehouse $warehouse;

    public string $mode = 'view';
    public string $htmlCondition;
    public array $itemData;
    public ?string $nextCursor = null;

    public bool $isShow = false;


    private WarehouseService $warehouseService;


    public function mount()
    {
        $this->warehouseService = app()->make(WarehouseService::class);
        $this->getDetailDataWarehouse($this->urlQuery);
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
        if (empty($this->urlQuery)) {
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
