<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Test extends Component
{
    public $readyToLoad = false;

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    public function mount()
    {
        
    }

    public function boot()
    {
        Log::info('boot');
    }

    public function placeHolder()
    {
        return '<h1>Load</h1>';
    }

    public function render()
    {

        Log::info('render test');

        return view('livewire.test');
    }
}
