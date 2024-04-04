<?php

namespace App\Livewire\Warehouse;

use App\Dto\UnitDTO;
use App\Repository\Compositions\UnitRepository;
use App\Service\Compositions\UnitService;
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
        $service = new UnitService(new UnitRepository());

        // buat unit DTO
        $unitDto = new UnitDTO(
            $this->code,
            $this->name
        );

        $result = $service->create($unitDto);
        if (!is_null($result)) {
            $this->reset();
            notify()->success('Sukses buat unit baru');
            $this->redirect("/composition/unit/view/{$result->id}", true);
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
