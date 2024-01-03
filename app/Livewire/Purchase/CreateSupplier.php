<?php

namespace App\Livewire\Purchase;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreateSupplier extends Component
{
    #[Rule('required|min:5|unique:suppliers,name')]
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $city = '';
    public string $country = '';
    public string $postalCode = '';
    public string $address = '';

    public function render()
    {
        return view('livewire.purchase.create-supplier');
    }

    /**
     * lakukan proses penyimpanan suppplier baru
     * validasi terlebih dahulu, kemudian simpan supplier baru
     * @return void
     */
    public function createSupplier()
    {

        $this->validate();
        $this->store();
    }


    private function store()
    {

        try {
            DB::beginTransaction();

            $supplier = \App\Models\Supplier::create([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'city' => $this->city,
                'country' => $this->country,
                'postal_code' => $this->postalCode,
                'address' => $this->address,
            ]);

            DB::commit();

            if ($supplier) {
                notify()->success('Berhasil membuat pemasok baru ', 'Sukses');
                $this->reset();
                return;
            }
            notify()->error('Gagal membuat pemasok baru', 'Gagal');
            return;

        } catch (Exception $exception) {
            notify()->error('Gagal membuat pemasok baru', 'Gagal');
            DB::rollBack();
            Log::error('Gagal membuat supplier baru');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

        }
    }


}


