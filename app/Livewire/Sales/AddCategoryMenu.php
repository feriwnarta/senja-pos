<?php

namespace App\Livewire\Sales;

use App\Models\CategoryMenu;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddCategoryMenu extends Component
{
    #[Validate('required|min:2')]
    public $code;

    #[Validate('required|min:2')]
    public $name;

    public function createCategoryMenu()
    {
        $category = CategoryMenu::create([
            'code' => $this->code,
            'name' => $this->name,
        ]);

        $this->dispatch('category-created', $category);

        $this->reset('code', 'name');

        return redirect()->to('/sales/list-category');
    }

    public function saveCategoryMenu()
    {
        $this->validate();
        $this->createCategoryMenu();
    }

    public function render()
    {
        return view('livewire.sales.add-category-menu');
    }
}
