<?php

namespace App\Livewire;

use Livewire\Attributes\Rule;
use Livewire\Component;

class Form extends Component
{

    #[Rule('required|min:5')]
    public $title = '';
    #[Rule('required')]
    public $content = '';

    public function save()
    {
        $this->validate();
    }

    public function render()
    {
        return view('livewire.form');
    }
}
