<?php

namespace App\Livewire\PointOfSalesKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.pos')]
class ModalPin extends Component
{
    public $screenNumber;
    public function render()
    {
        return view('livewire.point-of-sales-kasir.modal-pin');
    }

    public function backspace()
    {
    }
}
