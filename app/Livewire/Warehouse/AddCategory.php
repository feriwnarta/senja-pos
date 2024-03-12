<?php

namespace App\Livewire\Warehouse;

use App\Dto\CategoryDTO;
use App\Repository\Compositions\CategoryRepository;
use App\Service\Compositions\CategoryService;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AddCategory extends Component
{

    #[Rule('required|min:5|unique:categories,code')]
    public string $code;
    #[Rule('required|min:2|unique:categories,name')]
    public string $name;


    public function rendered()
    {

        $this->dispatch('set-width-title');
        $this->dispatch('load-script');

    }

    public function render()
    {
        return view('livewire.warehouse.add-category');
    }


    #[On('save-category')]
    public function save()
    {
        $this->validate([
            'code' => 'required|min:5|unique:categories,code',
            'name' => 'required|min:5|unique:categories,name',
        ]);


        $categoryDTO = new CategoryDTO(
            $this->code, $this->name
        );

        $service = new CategoryService(new CategoryRepository());
        $result = $service->createCategory($categoryDTO);
        $this->reset();
        $this->redirect("/composition/category-item/view/{$result->id}", true);

        notify()->success('Berhasil membuat kategori', 'Sukses');
    }


}
