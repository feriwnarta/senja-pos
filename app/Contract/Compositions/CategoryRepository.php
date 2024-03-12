<?php

namespace App\Contract\Compositions;

use App\Dto\CategoryDTO;
use App\Models\Category;

interface CategoryRepository
{

    public function create(CategoryDTO $categoryDTO): Category;

}
