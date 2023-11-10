<?php

namespace App\Service;

use App\Models\Warehouse;

interface WarehouseService
{

    public function getWarehouseById(string $id): Warehouse;

    public function getDetailDataAreaRackItemWarehouse(?Warehouse $warehouse): array;

    public function getItemRackByIdWithCursor(string $id): array;

    public function nextCursorItemRack(string $rackId, string $nextCursorId): array;

    public function getItemRackAddedByIdWithCursor(string $rackId): array;

    public function nextCursorItemRackAddedById(string $rackId, string $nextCursorId): array;

    public function manipulateItemRackAdded(array $dataItem, string $id): array;
}
