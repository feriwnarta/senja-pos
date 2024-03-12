<?php

namespace App\Service;


use App\Models\RecipeItem;
use Illuminate\Database\Eloquent\Collection;

interface RecipeService
{


    public function getAllItem(): ?Collection;

    public function selectMenuOrMaterial(bool $isMenu): ?Collection;


    public function saveRecipeItem(string $code, string $itemsId, array $recipes): ?RecipeItem;


}
