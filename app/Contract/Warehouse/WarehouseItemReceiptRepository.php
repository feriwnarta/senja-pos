<?php

namespace App\Contract\Warehouse;

use App\Models\CentralProduction;
use App\Models\CentralProductionFinish;
use App\Models\StockItem;
use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptRef;
use Illuminate\Database\Eloquent\Collection;

interface WarehouseItemReceiptRepository
{
    public function findWarehouseItemReceiptRefById(string $id): WarehouseItemReceiptRef;

    public function findWarehouseItemReceiptById(string $id): WarehouseItemReceipt;

    public function getWarehouseItemReceiptIdByRef(string $itemReceiptRefId): string;

    public function setCodeExistingWarehouseItemReceipt(string $warehouseItemReceiptId, string $code, int $increment): bool;

    public function getWarehouseCodeByWarehouseReceipt(string $warehouseItemReceiptId): string;

    public function getWarehouseIdByWarehouseReceipt(string $warehouseItemReceiptId): string;

    public function getLastCodeByWarehouse(string $warehouseId): array;

    public function updateAmountReceivedExistingDetails(array $itemDetails): bool;

    public function findProductionById(string $productionId): CentralProduction;

    public function getCostProduction(string $productionId, string $itemId): array;

    public function getItemRoute(string $itemId): string;

    public function getProductionRemaining(string $productionId, string $itemId): array;

    public function getWarehouseItemId(string $warehouseId, string $itemId): string;

    public function getStockItemByWarehouseItemId(string $warehouseItemId): ?StockItem;

    public function insertNewStockItem(string $warehouseItemId, array $cogsValuationCalcData): StockItem;

    public function getProductionFinishesByProductionId(string $productionId): CentralProductionFinish;

    public function getProductionFinishDetailsByProductionId(string $productionId): Collection;


}
