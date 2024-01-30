<?php

namespace App\Livewire\PointOfSalesKasir;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('components.layout.pos')]
class ModalPelangganBaru extends Component
{
    #[Rule('required|min:2|max:100')]
    public $name;
    #[Rule('required|decimal:10,13')]
    public $phoneNumber;
    #[Rule('required|email')]
    public $emailAddress;

    public function save()
    {
        // $this->validate();

        Customer::create([
            'name' => $this->name,
            'phoneNumber' => $this->phoneNumber,
            'emailAddress' => $this->emailAddress,
        ]);

        // $this->reset('name', 'phoneNumber', 'emailAddress');
    }

    public function render()
    {
        return view('livewire.point-of-sales-kasir.modal-pelanggan-baru');
    }
}
