<?php

namespace App\Livewire\Components\NavbarKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.pos')]
class HeaderPosKasir extends Component
{
    public $buttons_category = ["Semua", "Bubur", "Mie", "Bihun", "Kwetiau", "Sayur & Sup", "Nasi", "Senja Plus", "Ayam & Pork", "Telur & Seafood", "Senja Drink"];
    
    public function render()
    {
        return view('livewire.components.navbar-kasir.header-pos-kasir');
    }
}
