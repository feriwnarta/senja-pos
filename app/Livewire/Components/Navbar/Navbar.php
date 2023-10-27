<?php

namespace App\Livewire\Components\Navbar;

use Livewire\Component;

class Navbar extends Component
{
    public string $title;
    public bool $search = true;

    public function render()
    {
        return view('livewire.components.navbar.navbar');
    }
}
