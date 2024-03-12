<?php

namespace App\Livewire\Composition;

use App\Models\Category;
use Livewire\Component;

class ViewCategory extends Component
{
    public Category $category;

    public function mount(Category $category)
    {
        $this->category = $category;
    }


    public function render()
    {
        return view('livewire.composition.view-category');
    }
}
