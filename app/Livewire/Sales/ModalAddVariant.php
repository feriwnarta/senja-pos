<?php

namespace App\Livewire\Sales;

use Livewire\Component;

class ModalAddVariant extends Component
{
    public $name;
    public $price;
    public $SKU;

    public function move()
    {
        $dataVariant = [
            'variantName' => $this->name,
            'variantPrice' => $this->price,
            'variantSKU' => $this->SKU
        ];
        // ddd($dataVariant);
        $this->dispatch('variantSubmit', $dataVariant);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.sales.modal-add-variant');
    }
}
