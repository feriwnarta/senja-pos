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

    public function view(string $unitId)
    {
        $this->redirect("/composition/unit/view/$unitId", true);

    }

    public function render()
    {
        return view('livewire.warehouse.unit', ['units' => \App\Models\Unit::paginate(10)]);
    }
}
