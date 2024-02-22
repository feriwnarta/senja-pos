<?php

namespace App\Livewire\Sales;

use App\Models\Category;
use App\Models\CategoryMenu;
use App\Models\RestaurantMenu;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ListMenu extends Component
{
    use WithPagination;
    use WithFileUploads;
    // public $categoryMenus;

    // public function mount($id)
    // {
    //     $this->getCategoryName($id);
    // }

    // public function getCategoryName($id)
    // {
    //     $this->categoryMenus = CategoryMenu::findOrFail($id);
    // }


    public function render()
    {
        return view('livewire.sales.list-menu', ['restaurantMenus' => RestaurantMenu::paginate(10)]);
    }
}
