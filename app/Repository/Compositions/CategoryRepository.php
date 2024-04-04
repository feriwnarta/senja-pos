<?php

namespace App\Repository\Compositions;

use App\Dto\CategoryDTO;
use App\Models\Category;

class CategoryRepository implements \App\Contract\Compositions\CategoryRepository
{

    public function create(CategoryDTO $categoryDTO): Category
    {
        $category = new Category();
        $category->code = $categoryDTO->getCode();
        $category->name = $categoryDTO->getName();
        $category->save();

        return $category;
    }
}
