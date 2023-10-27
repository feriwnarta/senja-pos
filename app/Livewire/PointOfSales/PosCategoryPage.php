<?php

namespace App\Livewire\PointOfSales;

use Livewire\Component;

class PosCategoryPage extends Component
{

    public function mount()
    {
        sleep(5);
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="d-flex justify-content-center align-items-center position-absolute top-50 start-50">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        HTML;

    }

    public function render()
    {
        return view('livewire.point-of-sales.pos-category-page');
    }
}
