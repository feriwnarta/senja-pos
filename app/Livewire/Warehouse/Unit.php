<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;

class Unit extends Component
{

    public string $search = '';

    public function boot()
    {
        $this->dispatch('set-width-title');
    }

    public function render()
    {
        return view('livewire.warehouse.unit', ['units' => \App\Models\Unit::paginate(10)]);
    }
}
