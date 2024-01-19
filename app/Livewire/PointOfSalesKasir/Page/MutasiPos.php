<?php

namespace App\Livewire\PointOfSalesKasir\Page;

use Carbon\CarbonImmutable;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Mutasi')]
#[Layout('components.layouts.pos')]
class MutasiPos extends Component
{
    public function render()
    {
        return view('livewire.point-of-sales-kasir.page.mutasi-pos');
    }
}
