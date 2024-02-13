<?php

namespace App\Livewire\PointOfSalesKasir;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layout.pos')]
class ModalPelangganBaru extends Component
{
    #[Validate('required|min:2|max:100')]
    public $name = '';
    #[Validate('required|digits_between:10,13')]
    public $phoneNumber = '';
    #[Validate('required|email')]
    public $emailAddress = '';

    public function createCustomer()
    {
        // validate input
        // create customer
        // dispatch customer list that will be passed on the list pelanggan component

        $this->validate();

        $customers = Customer::create([
            'name' => $this->name,
            'phoneNumber' => $this->phoneNumber,
            'emailAddress' => $this->emailAddress,
        ]);

        $this->dispatch('customer-created', $customers);
    }

    public function savePelanggan()
    {
        // call createCustomer function
        // reset all input after sumbit
        
        $this->createCustomer();
        $this->reset('name', 'phoneNumber', 'emailAddress');
    }
    public function render()
    {
        return view('livewire.point-of-sales-kasir.modal-pelanggan-baru');
    }
}
