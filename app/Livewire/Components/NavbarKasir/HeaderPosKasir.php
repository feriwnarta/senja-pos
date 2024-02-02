<?php

namespace App\Livewire\Components\NavbarKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.pos')]
class HeaderPosKasir extends Component
{
    public $categories = [1 => 'Semua', 2 => 'Bubur', 3 => 'Mie', 4 => 'Bihun', 5 => 'Kwetiau', 6 => 'Sayur & Sup', 7 => 'Nasi', 8 => 'Senja Plus', 9 => 'Ayam & Pork', 10 => 'Telur & Seafood', 11 => 'Senja Drink'];
    public function render()
    {
        return view('livewire.components.navbar-kasir.header-pos-kasir');
    }
}
