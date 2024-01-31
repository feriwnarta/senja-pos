<?php

namespace App\Livewire\PointOfSalesKasir;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layout.pos")]
class ModalPilihPelanggan extends Component
{
    // protected $listeners = ['refresh-data' => 'refresh'];

    // public function mount()
    // {
    //     $this->dispatch('refresh-data');
    // }
    public $dataCustomers;

    public function render()
    {
        $this->dataCustomers = Customer::all();
        return view('livewire.point-of-sales-kasir.modal-pilih-pelanggan',[$this->dataCustomers]);
    }
}
