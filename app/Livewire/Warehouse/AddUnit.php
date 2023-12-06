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
    #[Rule('required|min:1|unique:units,name')]
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
                notify()->success('Berhasil membuat unit', 'Sukses');
                $this->reset();
                return;
            }

            notify()->error('Gagal membuat unit baru', 'Gagal');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            DB::rollBack();
            notify()->error('Gagal membuat unit baru', 'Gagal');
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
