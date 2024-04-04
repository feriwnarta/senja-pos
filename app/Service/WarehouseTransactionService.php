<?php

namespace App\Service;

use App\Models\RequestStock;
use App\Models\StockItem;

interface WarehouseTransactionService
{

    public function generateCodeRequest(bool $isOutlet, string $id): ?array;

    public function createRequest(bool $isOutlet, string $id, string $note = null): RequestStock;

    public function finishRequest(string $reqId, array $itemReq): string;

    public function reduceStockItemShipping(array $items, string $outboundId): ?StockItem;

    public function generateCodeShipping(string $warehouseId, string $warehouseCode): array;
}
