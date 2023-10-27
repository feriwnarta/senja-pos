<?php

namespace App\Livewire\PointOfSales;

use Livewire\Component;

class PosMenu extends Component
{
    public string $title = 'Menu';

    public bool $search = true;

    public function render()
    {
        return view('livewire.point-of-sales.pos-menu');
    }

    public function rendered($view, $html)
    {
        $this->dispatch('set-width-title');
    }
}
