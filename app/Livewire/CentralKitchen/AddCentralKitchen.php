<?php

namespace App\Livewire\CentralKitchen;

use App\Models\CentralKitchen;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AddCentralKitchen extends Component
{

    #[Rule('required|min:5|unique:central_kitchens,code')]
    public string $code;
    #[Rule('required|min:5')]
    public string $name;
    #[Rule('required|min:5')]
    public string $address;
    public string $phone;
    public string $email;


    public function render()
    {
        return view('livewire.central-kitchen.add-central-kitchen');
    }

    public function save()
    {
        $this->validate();

        $this->store();
    }

    public function store()
    {
        try {
            DB::beginTransaction();
            $result = CentralKitchen::create([
                'code' => $this->code,
                'name' => $this->name,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);

            DB::commit();

            if ($result) {
                $this->js("alert('berhasil tambah central kitchen')");
                $this->reset();
                return;
            }

            $this->js("alert('gagal tambah central kitchen')");
            $this->reset();

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('Exception dari penyimpanan central kitchen baru');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

        }

    }
}

