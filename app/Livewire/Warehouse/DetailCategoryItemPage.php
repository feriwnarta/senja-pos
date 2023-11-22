<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;

class DetailCategoryItemPage extends Component
{

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
        return view('livewire.warehouse.detail-category-item-page');
    }
}
