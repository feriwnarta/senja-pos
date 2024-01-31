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
    public $name;
    #[Validate('required|digits_between:10,13')]
    public $phoneNumber;
    #[Validate('required|email')]
    public $emailAddress;

    public function createCustomer()
    {
        $this->validate();

        Customer::create([
            'name' => $this->name,
            'phoneNumber' => $this->phoneNumber,
            'emailAddress' => $this->emailAddress,
        ]);
    }

    public function render()
    {
        $dataCustomers = Customer::all();
        return view('livewire.point-of-sales-kasir.modal-pelanggan-baru', ['data' => $dataCustomers]);
    }

    public function save()
    {
        $this->createCustomer();

        $this->reset('name', 'phoneNumber', 'emailAddress');

        $this->redirect('/pos/menu');
    }
}
