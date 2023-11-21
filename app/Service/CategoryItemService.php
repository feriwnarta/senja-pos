<?php

namespace App\Service;

interface CategoryItemService
{

    // dapatkan data item
    public function getItemCursor(array $id): array;

    public function getItemNextCursor(string $nextCursor): array;

}
