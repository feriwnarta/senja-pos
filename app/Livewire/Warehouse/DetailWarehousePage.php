<?php

namespace App\Livewire\Warehouse;

use Livewire\Attributes\Url;
use Livewire\Component;

class DetailWarehousePage extends Component
{
    #[Url(as: 'q')]
    public string $urlQuery;

    public string $locationWarehouse;
    public array $areas = [];
    public bool $isAddedArea = false;

    public function mount()
    {

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

    private function getDataWarehouse(string $id)
    {
        

    }
}
