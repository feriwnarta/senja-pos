<?php

namespace App\Livewire\Warehouse;

use Livewire\Attributes\Url;
use Livewire\Component;

class TransactionDetail extends Component
{

    #[Url(as: 'option', keep: true)]
    public string $option = '';

    #[Url(as: 'wouId', keep: true)]
    public string $outboundId = '';


    public function render()
    {
        return view('livewire.warehouse.transaction-detail');
    }
}
