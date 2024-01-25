<?php

namespace App\Livewire\PointOfSalesKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layouts.pos")]
class ModalPin extends Component
{
    public $screenNumber = null;

    public function render()
    {
        return view('livewire.point-of-sales-kasir.modal-pin');
    }

    public function digit($digit)
    {
        if ($this->screenNumber === null) {
            $this->screenNumber = $digit;
            
        } else {
            $this->screenNumber = $this->screenNumber.$digit;
        }
    }

    public function backspace()
    {

    }
}
