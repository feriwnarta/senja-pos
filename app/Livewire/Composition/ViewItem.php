<?php

namespace App\Livewire\Composition;

use Livewire\Component;

class ViewItem extends Component
{
    public \App\Models\Item $item;

    public function mount(\App\Models\Item $item)
    {
        $this->item = $item;
    }

    public function render()
    {
        return view('livewire.composition.view-item');
    }
}
