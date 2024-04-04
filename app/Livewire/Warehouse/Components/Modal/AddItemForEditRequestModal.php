<?php

namespace App\Livewire\Warehouse\Components\Modal;

use Livewire\Component;

class AddItemForEditRequestModal extends Component
{
    public $requestStockId;

    public function mount($requestStockId)
    {
        
    }


    public function render()
    {
        return view('livewire.warehouse.components.modal.add-item-for-edit-request-modal');
    }
}
