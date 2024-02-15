<?php

namespace App\Livewire\Sales;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddMenu extends Component
{
    use WithFileUploads;

    #[Validate('image|max:1024')]
    public $thumbnail;

    public function render()
    {
        return view('livewire.sales.add-menu');
    }
}
