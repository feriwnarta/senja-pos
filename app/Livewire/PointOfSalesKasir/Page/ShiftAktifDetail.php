<?php

namespace App\Livewire\PointOfSalesKasir\Page;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Shift Aktif Detail")]
#[Layout("components.layouts.pos")]
class ShiftAktifDetail extends Component
{
    public function render()
    {
        return view('livewire.point-of-sales-kasir.page.shift-aktif-detail');
    }
}
