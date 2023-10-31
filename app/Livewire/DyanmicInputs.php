<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class DyanmicInputs extends Component
{
    public Collection $inputs;

    public function addInput()
    {
        $this->inputs->push(['email' => '']);
    }


    public function removeInput($key)
    {
        $this->inputs->pull($key);
    }

    public function mount()
    {
        $this->fill([
            'inputs' => collect([['email' => '']]),
        ]);
    }

    public function render()
    {
        return view('livewire.dyanmic-inputs');
    }
}
