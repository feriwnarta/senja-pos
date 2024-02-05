<?php

namespace App\Livewire\PointOfSalesKasir\Page;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Pesanan Aktif')]
#[Layout('components.layouts.pos')]
class AktifOrderPos extends Component
{
    public function render()
    {
        return view('livewire.point-of-sales-kasir.page.aktif-order-pos');
    }
}
