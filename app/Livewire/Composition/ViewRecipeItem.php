<?php

namespace App\Livewire\Composition;

use App\Models\RecipeItem;
use Livewire\Component;

class ViewRecipeItem extends Component
{

    public RecipeItem $recipeItem;

    public function mount(RecipeItem $recipeItem)
    {
        $this->recipeItem = $recipeItem;

    }

    public function render()
    {
        return view('livewire.composition.view-recipe');
    }
}
