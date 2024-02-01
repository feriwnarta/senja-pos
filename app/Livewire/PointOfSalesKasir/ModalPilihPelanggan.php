<?php

namespace App\Livewire\PointOfSalesKasir;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout("components.layout.pos")]
class ModalPilihPelanggan extends Component
{
    // get list pelanggan using On Attr and customer-created params
    // make a function to make it listed

    #[On('customer-created')]
    public function updateListCustomer($customersList)
    {
    }

    public function render()
    {
        // calling data customer in the second params
        
        return view('livewire.point-of-sales-kasir.modal-pilih-pelanggan',['data'=>Customer::first()->get()]);
    }
}
