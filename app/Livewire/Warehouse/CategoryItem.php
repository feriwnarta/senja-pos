<?php

namespace App\Livewire\Warehouse;

use App\Models\Category;
use Livewire\Component;

class CategoryItem extends Component
{

    public string $search = '';

    public function rendered()
    {
        $this->dispatch('set-width-title');
    }

    public function view(string $categoryId)
    {
        $this->redirect("/composition/category-item/view/$categoryId", true);
    }

    public function render()
    {
        return view('livewire.warehouse.category-item', [
            'categories' => Category::paginate(10)
        ]);
    }
}
