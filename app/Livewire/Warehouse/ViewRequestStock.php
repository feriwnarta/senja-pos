<?php

namespace App\Livewire\Warehouse;

use App\Models\RequestStock;
use Livewire\Component;

class ViewRequestStock extends Component
{

    public RequestStock $requestStock;
    public bool $isEdited = false;

    public function mount(RequestStock $requestStock)
    {
        $this->requestStock = $requestStock;
    }

    public function render()
    {
        return view('livewire.warehouse.view-request-stock');
    }

    public function editRequestStock()
    {


    }
}
