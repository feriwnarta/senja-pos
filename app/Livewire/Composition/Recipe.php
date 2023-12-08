<?php

namespace App\Livewire\Composition;

use Livewire\Component;

class Recipe extends Component
{

    public string $typeRecipe = 'recipeMenu';

    public function createRecipe()
    {

        if ($this->typeRecipe != 'recipeMenu' && $this->typeRecipe != 'recipeSemi') {
            return;
        }

        $this->redirect("/composition/recipe/create-recipe/?type={$this->typeRecipe}", true);
    }

    public function render()
    {
        return view('livewire.composition.recipe');
    }
}
