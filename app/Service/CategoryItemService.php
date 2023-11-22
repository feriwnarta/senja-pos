<?php

namespace App\Service;

use App\Models\Category;

interface CategoryItemService
{

    // dapatkan data item
    public function getItemCursor(array $id): array;

    public function getItemNextCursor(string $nextCursor): array;

    public function saveCategory(string $code, string $name, string $unit, array $items): Category;

}
