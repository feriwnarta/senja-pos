<?php

namespace App\Livewire\Sales;

use App\Models\RestaurantMenu;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddMenu extends Component
{
    use WithFileUploads;

    #[Validate('required|image|max:1024')]
    public $thumbnail;
    #[Validate('required|min:2')]
    public $code;
    #[Validate('required|min:2')]
    public $name;
    // Nullable
    public $description;
    #[Validate('required|numeric|min:0')]
    public $price;
    #[Validate('required|min:2')]
    public $sku;
    public $result = null;

    public function createMenu()
    {

        if ($this->thumbnail !== null) {
            $this->result = $this->thumbnail->store('public/item-image');
        }

        $restaurantMenu = RestaurantMenu::create([
            'code' => $this->code,
            'thumbnail' => $this->result,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'sku' => $this->sku
        ]);

        $this->dispatch("menu-created", $restaurantMenu);
        $this->reset();

        // return $this->redirect('/sales/list-menu');
    }

    public function save()
    {
        $this->validate();

        $this->createMenu();
    }

    public function render()
    {
        return view('livewire.sales.add-menu');
    }
}
