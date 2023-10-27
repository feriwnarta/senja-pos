<?php

namespace App\Livewire\Components\Sidebar;

use Livewire\Component;

class Sidebar extends Component
{
    public function mount()
    {
        $this->dispatch('update-menu');
    }

    public function render()
    {
        return view('livewire.components.sidebar.sidebar');
    }
}
