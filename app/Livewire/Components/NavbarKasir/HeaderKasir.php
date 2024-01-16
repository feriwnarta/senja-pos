<?php

namespace App\Livewire\Components\NavbarKasir;

use Illuminate\Routing\Route;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layouts.pos")]
class HeaderKasir extends Component
{
    public function render()
    {
        return view('livewire.components.navbar-kasir.header-kasir');
    }
}
