<?php

namespace App\Livewire\PointOfSalesKasir\Page;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layouts.pos")]
class ShiftAktifDetail extends Component
{
    public function render()
    {
        return view('livewire.point-of-sales-kasir.page.active-shift-detail');
    }
}
