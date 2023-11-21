<?php

namespace App\Livewire\Warehouse;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;


class AddUnit extends Component
{


    #[Rule('required|min:5|unique:units,code')]
    public string $code;
    #[Rule('required|min:5|unique:units,name')]
    public string $name;


    #[On('saveUnit')]
    public function saveUnit()
    {
        $this->validate();
        $this->storeUnit();
    }

    private function storeUnit()
    {

        try {
            DB::beginTransaction();

            $unit = \App\Models\Unit::create([
                'code' => $this->code,
                'name' => $this->name,
            ]);

            DB::commit();

            if ($unit) {
                $this->js("alert('berhasil simpan unit')");
                $this->reset();
                return;
            }
            $this->js("alert('gagal simpan unit')");

        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function boot()
    {
        $this->dispatch('set-width-title');
    }

    public function render()
    {
        return view('livewire.warehouse.add-unit');
    }
}
