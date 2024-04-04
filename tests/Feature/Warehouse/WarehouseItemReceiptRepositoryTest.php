<?php

namespace Tests\Feature\Warehouse;

use App\Contract\Warehouse\WarehouseItemReceiptRepository;
use App\Models\CentralProduction;
use App\Models\Item;
use App\Models\StockItem;
use App\Models\Warehouse;
use App\Models\WarehouseItem;
use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptDetail;
use App\Models\WarehouseItemReceiptHistory;
use App\Models\WarehouseItemReceiptRef;
use Database\Seeders\WarehouseItemReceiptSeederProduction;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertIsString;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

class WarehouseItemReceiptRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private WarehouseItemReceiptRepository $repository;

    public function testFindWarehouseItemReceiptRefByIdSuccess()
    {
        $itemReceipt = WarehouseItemReceiptRef::first();

        $result = $this->repository->findWarehouseItemReceiptRefById($itemReceipt->id);
        self::assertNotNull($result);
        self::assertInstanceOf(WarehouseItemReceiptRef::class, $result);
        self::assertEquals($itemReceipt->id, $result->id);
    }

    public function testFindWarehouseItemReceiptRefByIdFailed()
    {
        $this->expectException(Exception::class);
        $result = $this->repository->findWarehouseItemReceiptRefById('asalasalan');
    }

    public function testFindWarehouseItemReceiptByIdSuccess()
    {
        $itemReceipt = WarehouseItemReceipt::first();
        $result = $this->repository->findWarehouseItemReceiptById($itemReceipt->id);
        assertNotNull($result);
        assertInstanceOf(WarehouseItemReceipt::class, $result);
        assertEquals($itemReceipt->id, $result->id);

    }

    public function testFindWarehouseItemReceiptByIdFailed()
    {
        $this->expectException(Exception::class);
        $result = $this->repository->findWarehouseItemReceiptById('asalasalan');
    }

    public function testSetCodeExistingWarehouseItemReceiptSuccess()
    {
        $itemReceipt = WarehouseItemReceipt::first();
        $code = fake()->countryCode;
        $increment = 1;
        $result = $this->repository->setCodeExistingWarehouseItemReceipt($itemReceipt->id, $code, $increment);
        self::assertTrue($result);

        $result = $this->repository->findWarehouseItemReceiptById($itemReceipt->id);
        assertEquals($result->code, $code);
        assertEquals($result->increment, $increment);
    }

    public function testSetCodeExistingWarehouseItemReceiptFailedItemReceiptNotFound()
    {
        $this->expectException(Exception::class);
        $code = fake()->countryCode;
        $increment = 1;
        $result = $this->repository->setCodeExistingWarehouseItemReceipt('idasalasalan', $code, $increment);
    }

    public function testSetCodeExistingWarehouseItemReceiptFailedCodeSame()
    {
        $itemReceipt = WarehouseItemReceipt::first();
        $code = fake()->countryCode;
        $increment = 1;

        $result = $this->repository->setCodeExistingWarehouseItemReceipt($itemReceipt->id, $code, $increment);
        assertTrue($result);

        $this->seed([WarehouseItemReceiptSeederProduction::class]);
        $itemReceipt2 = WarehouseItemReceipt::whereNotIn('id', [$itemReceipt->id])->get()->first();
        Log::info($itemReceipt2);
        self::assertCount(2, WarehouseItemReceipt::all());
        assertNotNull($itemReceipt2);
        assertNotEquals($itemReceipt->id, $itemReceipt2->id);

        $this->expectException(UniqueConstraintViolationException::class);
        $result = $this->repository->setCodeExistingWarehouseItemReceipt($itemReceipt2->id, $code, 2);

    }


    public function testUpdateAmountReceivedExistingDetailsSuccess()
    {
        $result = WarehouseItemReceiptDetail::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'qty_accept' => 10,
            ];
        })->toArray();
        self::assertIsArray($result);
        Log::info($result);

        $result = $this->repository->updateAmountReceivedExistingDetails($result);
        assertTrue($result);
    }

    public function testUpdateAmountReceivedExistingDetailsFailedWhenReceiptDetailNotFound()
    {
        $result = WarehouseItemReceiptDetail::all()->map(function ($item) {
            return [
                'id' => fake()->uuid,
                'qty_accept' => 10,
            ];
        })->toArray();
        self::assertIsArray($result);

        $this->expectException(ModelNotFoundException::class);
        $result = $this->repository->updateAmountReceivedExistingDetails($result);
    }

    public function testUpdateAmountReceivedExistingDetailsFailedWhenQtyAcceptNotDecimal()
    {
        $result = WarehouseItemReceiptDetail::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'qty_accept' => fake()->name,
            ];
        })->toArray();
        self::assertIsArray($result);

        $this->expectException(QueryException::class);
        $result = $this->repository->updateAmountReceivedExistingDetails($result);
    }

    public function testGetCostProductionSuccess()
    {
        $production = CentralProduction::first();
        $production->finishes()->create([
            'item_id' => Item::first()->id,
            'amount_target' => 10,
            'amount_reached' => 10
        ]);
        assertNotNull($production);

        $details = $production->finishes->first()->details()->create([
            'item_id' => Item::first()->id,
            'amount_used' => 100,
            'avg_cost' => 1000,
            'last_cost' => 1.200,
        ]);
        assertNotNull($details);


        $itemId = Item::first()->id;

        $result = $this->repository->getCostProduction($production->id, $itemId);
        print_r($result);
        $totalCost = $result['total_cost'];
        assertNotNull($totalCost);
        assertIsArray($result);
        assertEquals(200000, $totalCost);
        assertEquals($production->id, $result['production_id']);
    }

    public function testGetCostProductionFailedProductionIdNotFound()
    {
        $this->expectException(Exception::class);
        $result = $this->repository->getCostProduction(fake()->uuid, fake()->uuid);
    }

    public function testGetCostProductionFailedItemIdNotFound()
    {

        $production = CentralProduction::first();
        $production->finishes()->create([
            'item_id' => Item::first()->id,
            'amount_target' => 10,
            'amount_reached' => 10
        ]);
        assertNotNull($production);

        $details = $production->finishes->first()->details()->create([
            'item_id' => Item::first()->id,
            'amount_used' => 100,
            'avg_cost' => 1000,
            'last_cost' => 1.200,
        ]);
        assertNotNull($details);


        $itemId = Item::first()->id;

        $this->expectException(Exception::class);
        $result = $this->repository->getCostProduction($production->id, fake()->uuid);

    }

    public function testGetItemRouteSuccess()
    {

        $item = Item::first();

        $result = $this->repository->getItemRoute($item->id);
        self::assertIsString($result);

    }

    public function testGetProductionRemainingSuccess()
    {
        $production = CentralProduction::first();
        assertNotNull($production);
        $productionRemaining = $production->remaining()->create();
        assertNotNull($productionRemaining);

        $productionRemainingDetail = $productionRemaining->detail()->create([
            'items_id' => Item::first()->id,
            'qty_remaining' => 10,
            'avg_cost' => 4000,
            'last_cost' => 4200,
        ]);

        $productionId = $production->id;
        $itemId = Item::first()->id;


        $result = $this->repository->getProductionRemaining($productionId, $itemId);
        assertIsArray($result);
        print_r($result);
        self::assertEquals(4000.00, $result['avg_cost']);
        self::assertEquals(10.0, $result['qty']);
        self::assertEquals($productionId, $result['production_id']);
        self::assertEquals($itemId, $result['item_id']);

    }

    public function testGetProductionRemainingFailedRemainingNotFound()
    {
        $production = CentralProduction::first();
        assertNotNull($production);

        $productionId = $production->id;
        $itemId = Item::first()->id;

        $result = $this->repository->getProductionRemaining($productionId, $itemId);
        assertIsArray($result);
        self::assertEmpty($result);
    }

    public function testGetProductionRemainingFailedDetailRemainingNotFound()
    {
        $production = CentralProduction::first();
        assertNotNull($production);

        $productionRemaining = $production->remaining()->create();
        assertNotNull($productionRemaining);

        $productionId = $production->id;
        $itemId = Item::first()->id;
        
        $result = $this->repository->getProductionRemaining($productionId, $itemId);
        self::assertEmpty($result);
    }

    public function testGetWarehouseItemReceiptById()
    {

        $itemReceiptRef = WarehouseItemReceiptRef::first();
        $itemReceiptId = $itemReceiptRef->itemReceipt->id;

        $result = $this->repository->getWarehouseItemReceiptIdByRef($itemReceiptRef->id);
        self::assertIsString($result);
        assertEquals($itemReceiptId, $result);
    }

    public function testGetWarehouseCodeByWarehouseReceiptSuccess()
    {
        $ref = WarehouseItemReceipt::first();
        $warehouseCode = $ref->warehouse->warehouse_code;
        self::assertIsString($warehouseCode);
        assertNotNull($ref);
        $code = $this->repository->getWarehouseCodeByWarehouseReceipt($ref->id);
        assertIsString($code);
        assertEquals($warehouseCode, $code);

    }

    public function testGetWarehouseCodeByWarehouseReceiptFailedModelNotFound()
    {

        $this->expectException(ModelNotFoundException::class);
        $code = $this->repository->getWarehouseCodeByWarehouseReceipt(fake()->uuid);
    }

    public function testGetWarehouseIdByWarehouseReceiptSuccess()
    {
        $ref = WarehouseItemReceipt::first();
        $warehouseId = $ref->warehouse->id;
        self::assertIsString($warehouseId);
        assertNotNull($ref);
        $code = $this->repository->getWarehouseIdByWarehouseReceipt($ref->id);
        assertIsString($code);
        assertEquals($warehouseId, $code);

    }

    public function testGetWarehouseItemIdSuccess()
    {

        $item = Item::first();
        assertNotNull($item);
        $warehouse = Warehouse::first();
        assertNotNull($warehouse);

        $warehouseItem = WarehouseItem::create([
            'warehouses_id' => $warehouse->id,
            'items_id' => $item->id
        ]);
        assertNotNull($warehouseItem);

        $result = $this->repository->getWarehouseItemId($warehouse->id, $item->id);
        assertIsString($result);
        assertEquals($warehouseItem->id, $result);
    }

    public function testGetStockItemByWarehouseItemIdSuccess()
    {

        $item = Item::first();
        assertNotNull($item);
        $warehouse = Warehouse::first();
        assertNotNull($warehouse);

        $warehouseItem = WarehouseItem::create([
            'warehouses_id' => $warehouse->id,
            'items_id' => $item->id
        ]);

        $stockItem = StockItem::create([
            'warehouse_items_id' => $warehouseItem->id,
            'incoming_qty' => fake()->randomFloat(),
            'incoming_value' => fake()->randomFloat(),
            'price_diff' => 0,
            'inventory_value' => 0,
            'qty_on_hand' => 10,
            'avg_cost' => 3000,
            'last_cost' => 3200,
            'minimum_stock' => 3100,
        ]);

        assertNotNull($stockItem);

        $warehouseItemId = $this->repository->getWarehouseItemId($warehouse->id, $item->id);
        assertEquals($warehouseItem->id, $warehouseItemId);
    }

    public function testGetProductionFinishesByProductionIdSuccess()
    {
        $production = CentralProduction::first();
        assertNotNull($production);
        $this->repository->getProductionFinishesByProductionId($production->id);
    }

    public function testGetProductionFinishesByProductionIdFailed()
    {
        $this->expectException(Exception::class);
        $this->repository->getProductionFinishesByProductionId(fake()->uuid);
    }

    public function testGetProductionFinishDetailsByProductionId()
    {
        $production = CentralProduction::first();
        assertNotNull($production);
        $result = $this->repository->getProductionFinishDetailsByProductionId($production->id);
        assertNotNull($result);
    }

    public function testInsertNewStockItemSuccess()
    {
        $cogsValuationCalcData = [
            'incoming_qty' => 10,
            'incoming_value' => 10000,
            'price_diff' => 0,
            'inventory_value' => 12000,
            'qty_on_hand' => 12,
            'avg_cost' => 1000,
            'last_cost' => 1000
        ];

        $item = Item::first();
        assertNotNull($item);
        $warehouse = Warehouse::first();
        assertNotNull($warehouse);

        $warehouseItem = WarehouseItem::create([
            'warehouses_id' => $warehouse->id,
            'items_id' => $item->id
        ]);
        assertNotNull($warehouseItem);

        $result = $this->repository->insertNewStockItem($warehouseItem->id, $cogsValuationCalcData);
        assertNotNull($result);
        assertInstanceOf(StockItem::class, $result);

    }

    public function testCreateWarehouseItemReceiptHistorySuccess()
    {
        $itemReceipt = WarehouseItemReceipt::first();
        assertNotNull($itemReceipt);

        $result = $this->repository->createWarehouseItemReceiptHistory($itemReceipt->id, 'test', 'test');
        assertInstanceOf(WarehouseItemReceiptHistory::class, $result);

        assertEquals($itemReceipt->id, $result->warehouse_item_receipts_id);
    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new \App\Repository\Warehuouse\WarehouseItemReceiptRepository();

        // seeder dummy data
        $this->seed([WarehouseItemReceiptSeederProduction::class]);

    }


}
