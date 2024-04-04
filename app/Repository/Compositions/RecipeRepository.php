<?php

namespace App\Repository\Compositions;

use App\Models\RecipeItem;

class RecipeRepository implements \App\Contract\Compositions\RecipeRepository
{

    public function showRecipeTypeMaterial()
    {
        return RecipeItem::with(['item', 'recipeDetail'])->paginate(10);
    }
}
