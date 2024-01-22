<?php

namespace App\Livewire\PointOfSalesKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layouts.pos")]
class ModalPembayaranBerhasil extends Component
{
    public function render()
    {
        return view('livewire.point-of-sales-kasir.modal-pembayaran-berhasil');
    }
}
