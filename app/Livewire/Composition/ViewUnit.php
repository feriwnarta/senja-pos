<?php

namespace App\Livewire\Composition;

use App\Models\Unit;
use Livewire\Component;

class ViewUnit extends Component
{
    public Unit $unit;

    public function mount(Unit $unit)
    {
        $this->unit = $unit;
    }

    public function render()
    {
        return view('livewire.composition.view-unit');
    }
}
