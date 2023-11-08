<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use App\Service\WarehouseService;
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

    public function mount()
    {
        $this->getDetailDataWarehouse($this->urlQuery);

    }

    private function getDetailDataWarehouse(string $id)
    {

        $warehouseService = app()->make(WarehouseService::class);

        // jika id nya kosong
        if (empty($this->urlQuery)) {
            return;
        }

        try {
            $this->warehouse = $warehouseService->getDetailWarehouse($id);
        } catch (\Exception $e) {
            // warehouse not found
            if ($e->getCode() == 1) {

            }

            if ($e->getCode() == 2) {
                
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

    public function render()
    {
        return view('livewire.warehouse.detail-warehouse-page');
    }


}
