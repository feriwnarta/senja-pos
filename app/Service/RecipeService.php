<?php

namespace App\Service;


use Illuminate\Database\Eloquent\Collection;

interface RecipeService
{


    public function getAllItem(): ?Collection;

    public function selectMenuOrMaterial(bool $isMenu): ?Collection;


}
