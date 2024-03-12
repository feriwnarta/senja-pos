<?php

namespace App\Contract\Compositions;

use App\Dto\CategoryDTO;
use App\Models\Category;

interface CategoryService
{

    public function createCategory(CategoryDTO $categoryDTO): Category;
}
