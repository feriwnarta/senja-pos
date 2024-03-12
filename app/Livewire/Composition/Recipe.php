<?php

namespace App\Livewire\Composition;

use App\Contract\Compositions\RecipeRepository;
use Livewire\Component;

class Recipe extends Component
{
    public string $typeRecipe = 'recipeMenu';
    private RecipeRepository $recipeRepository;


    public function createRecipe()
    {

        if ($this->typeRecipe != 'recipeMenu' && $this->typeRecipe != 'recipeSemi') {
            return;
        }

        $this->redirect("/composition/recipe/create-recipe/?type={$this->typeRecipe}", true);
    }

    public function boot()
    {
        $this->recipeRepository = new \App\Repository\Compositions\RecipeRepository();
    }

    public function render()
    {

        return view('livewire.composition.recipe', [
            'recipes' => $this->handleRecipeTypeChange(),
        ]);
    }

    private function handleRecipeTypeChange()
    {
        if ($this->typeRecipe == 'recipeSemi') {
            return $this->recipeRepository->showRecipeTypeMaterial();
        }
    }

    public function view(string $recipeId)
    {
        $this->redirect("/composition/recipe/view/$recipeId", true);
    }


}
