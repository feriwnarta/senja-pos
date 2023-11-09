<?php

namespace App\Service;

use App\Models\Warehouse;

interface WarehouseService
{

    public function getWarehouseById(string $id): Warehouse;

    public function getDetailDataAreaRackItemWarehouse(?Warehouse $warehouse): array;

    public function getItemRackByIdWithCursor(string $id): array;

    public function nextCursorItemRack(string $rackId, string $cursorId): array;
}
