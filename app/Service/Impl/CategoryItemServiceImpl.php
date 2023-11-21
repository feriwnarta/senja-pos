<?php

namespace App\Service\Impl;

use App\Models\Item;
use App\Service\CategoryItemService;

class CategoryItemServiceImpl implements CategoryItemService
{

    public function getItemCursor(array $id): array
    {

        return Item::whereNotIn('id', $id)->orderBy('id')->cursorPaginate(10)->toArray();

    }

    public function getItemNextCursor(string $nextCursor): array
    {
        return Item::orderBy('id')->cursorPaginate(10, ['*'], 'cursor', $nextCursor)->toArray();
    }
}
