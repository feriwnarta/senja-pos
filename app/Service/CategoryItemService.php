<?php

namespace App\Service;

interface CategoryItemService
{

    // dapatkan data item
    public function getItemCursor(): array;

    public function getItemNextCursor(string $nextCursor): array;

}
