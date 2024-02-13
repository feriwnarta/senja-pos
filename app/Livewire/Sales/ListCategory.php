<?php

namespace App\Livewire\Sales;

use App\Models\CategoryMenu;
use Livewire\Component;
use Livewire\WithPagination;

class ListCategory extends Component
{
    use WithPagination;

    public function updateListCategoryMenu($category)
    {
    }

    public function render()
    {
        return view('livewire.sales.list-category', ['categoryMenus' => CategoryMenu::paginate(10)]);
    }
}
