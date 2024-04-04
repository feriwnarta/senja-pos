<?php

namespace App\Livewire\Purchase;

use Livewire\Component;

class Supplier extends Component
{
    public function render()
    {
        return view('livewire.purchase.supplier', [
            'suppliers' => \App\Models\Supplier::paginate(10)
        ]);
    }
}
