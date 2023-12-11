<?php

namespace App\Livewire\Warehouse;

use Livewire\Attributes\Url;
use Livewire\Component;

class CreateTransaction extends Component
{

    #[Url(as: 'option', keep: true)]
    public string $option = '';
    #[Url(as: 'type', keep: true)]
    public string $type = '';
    #[Url(as: 'id', keep: true)]
    public string $id = '';

    public string $error = '';

    public function render()
    {
        return view('livewire.warehouse.create-transaction');
    }

    public function boot()
    {
        $this->extractUrl();
    }

    private function extractUrl()
    {
        if ($this->option == null && $this->type == null && $this->id == null) {
            $this->error = 'Ada yang salah dengan format url, kembali dan masuk lagi';
            return;
        }

        if ($this->type != 'outlet' && $this->type != 'centralKitchen') {
            $this->error = 'Pastikan tipe adalah outlet atau central kitchen';
            return;
        }
        
    }
}
