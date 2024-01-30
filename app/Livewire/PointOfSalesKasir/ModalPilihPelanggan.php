<?php

namespace App\Livewire\PointOfSalesKasir;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layout.pos")]
class ModalPilihPelanggan extends Component
{
    public function render()
    {
        $dataPelanggan = Customer::all();
        return view('livewire.point-of-sales-kasir.modal-pilih-pelanggan',[
            'data' => $dataPelanggan,
        ]);
    }
}
