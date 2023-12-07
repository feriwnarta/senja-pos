<?php

namespace App\Livewire\Composition;

use Livewire\Attributes\Url;
use Livewire\Component;
use Ramsey\Collection\Collection;

class CreateRecipe extends Component
{

    #[Url(as: 'type', keep: true)]
    public string $type = '';

    public string $code;

    public array $component = [];

    public Collection $items;

    public string $selectItem = '';

    public function mount()
    {

        $result = $this->extractUrl($this->type);
        $this->type = $result;

    }
    

    public function extractUrl(string $url)
    {
        if ($url != 'recipeMenu' && $url != 'recipeSemi') {
            return 'recipeMenu';
        }

        return $url;
    }


    public function render()
    {
        return view('livewire.composition.create-recipe');
    }
}
