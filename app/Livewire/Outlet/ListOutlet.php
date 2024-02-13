<?php

namespace App\Livewire\Outlet;

use App\Models\Outlet;
use Livewire\Component;

class ListOutlet extends Component
{    public function render()
    {
        return view('livewire.outlet.list-outlet', ['outlets' => Outlet::paginate(10)]);
    }
}
