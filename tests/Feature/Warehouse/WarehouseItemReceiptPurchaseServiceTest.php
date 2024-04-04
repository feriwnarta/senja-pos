<?php

namespace Tests\Feature\Warehouse;

use App\Models\StockItem;
use App\Repository\Warehuouse\WarehouseItemReceiptRepository;
use App\Service\Warehouse\WarehouseItemReceiptPurchaseService;
use Mockery\MockInterface;
use ReflectionClass;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class WarehouseItemReceiptPurchaseServiceTest extends TestCase
{

    private WarehouseItemReceiptPurchaseService $service;


    public function testCalculateTotalCostSuccess()
    {

        $dataItemPurchase = [
            [
                'item_id' => 1,
                'singlePrice' => 1000,
            ],
            [
                'item_id' => 2,
                'singlePrice' => 1000,
            ],
        ];

        $this->service = new WarehouseItemReceiptPurchaseService(new WarehouseItemReceiptRepository());
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $method = $reflection->getMethod('calculateTotalCost');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->service, [$dataItemPurchase, 2, 5]);
        self::assertIsArray($result);
        assertEquals(5000, $result['total']);
        assertEquals(1000, $result['last_cost']);
    }

    public function testCalculateTotalCostIdNotSame()
    {

        $dataItemPurchase = [
            [
                'item_id' => 1,
                'singlePrice' => 1000,
            ],
            [
                'item_id' => 2,
                'singlePrice' => 1000,
            ],
        ];

        $this->service = new WarehouseItemReceiptPurchaseService(new WarehouseItemReceiptRepository());
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $method = $reflection->getMethod('calculateTotalCost');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->service, [$dataItemPurchase, 4, 5]);
        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    public function testCalculateAvgCostSuccess()
    {
        $this->service = new WarehouseItemReceiptPurchaseService(new WarehouseItemReceiptRepository());
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $method = $reflection->getMethod('calculateAvgCost');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [10000, 1000]);
        assertEquals(10, $result);
    }

    public function testGetCogsValuationCalcDataInitialAvgSuccess()
    {
        $this->service = new WarehouseItemReceiptPurchaseService(new WarehouseItemReceiptRepository());
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);


        $method = $reflection->getMethod('getCogsValuationCalcData');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [10, 3200, true, new StockItem()]);
        assertNotNull($result);
        self::assertIsArray($result);

        $expectedQtyOnHand = 10;
        $expectedAvgCost = 3200;
        $expectedLastCost = 3200;

        assertEquals($expectedQtyOnHand, $result['qty_on_hand']);
        assertEquals($expectedAvgCost, $result['avg_cost']);
        assertEquals($expectedLastCost, $result['last_cost']);
    }

    public function testGetCogsValuationCalcDataCalculateAvgSuccess()
    {
        $this->service = new WarehouseItemReceiptPurchaseService(new WarehouseItemReceiptRepository());
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $stockItem = new StockItem();
        $stockItem->inventory_value = 0;
        $stockItem->qty_on_hand = 100;
        $stockItem->avg_cost = 3000;


        $method = $reflection->getMethod('getCogsValuationCalcData');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [10, 3300, false, $stockItem]);
        assertNotNull($result);
        self::assertIsArray($result);

        $expectedQtyOnHand = 110;
        $expectedAvgCost = 3027.27;

        assertEquals($expectedQtyOnHand, $result['qty_on_hand']);
        assertEquals($expectedAvgCost, $result['avg_cost']);
    }

    public function testInsertStockValuationInitialAvgCostSuccess()
    {
        $dataItemPurchase = [
            [
                'item_id' => 1,
                'singlePrice' => 1000,
            ],
            [
                'item_id' => 2,
                'singlePrice' => 1000,
            ],
        ];

        $dataItemDetails = [
            [
                'item_id' => 2,
                'qty_accept' => 10
            ]
        ];

        $repository = $this->mock(\App\Contract\Warehouse\WarehouseItemReceiptRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getWarehouseItemId')->once()->andReturn(fake()->uuid);
            $mock->shouldReceive('getStockItemByWarehouseItemId')->once()->andReturn(null);
            $mock->shouldReceive('insertNewStockItem')->once()->andReturn(new StockItem());
        });

        $this->service = new WarehouseItemReceiptPurchaseService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $method = $reflection->getMethod('insertStockValuation');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [fake()->uuid, $dataItemPurchase, $dataItemDetails]);
        self::assertInstanceOf(StockItem::class, $result);


    }


}
