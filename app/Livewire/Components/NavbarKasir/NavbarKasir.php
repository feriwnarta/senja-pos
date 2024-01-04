<?php

namespace App\Livewire\Components\NavbarKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.pos')] 
class NavbarKasir extends Component
{
    public function render()
    {
        return view('livewire.components.navbar-kasir.navbar-kasir');
    }
}
