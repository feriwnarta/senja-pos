<?php

namespace Tests\Feature;

use App\Service\Impl\CogsValuationCalc;
use App\Service\InventoryValuationCalc;
use Tests\TestCase;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertNotNull;

class CogsValuationTest extends TestCase
{

    private InventoryValuationCalc $calc;

    // test input nilai avg cost awal
    public function testStockAwal()
    {
        $stockAwal = 10;
        $avgCost = 12.500;

        $result = $this->calc->initialAvg($stockAwal, $avgCost);
        assertNotNull($result);
        self::assertIsArray($result);
        self::assertArrayHasKey('inventory_value', $result);
        self::assertArrayHasKey('incoming_value', $result);
        print_r($result);

    }

    public function testCalculateAvgCostInstock()
    {

        $result = $this->calc->calculateAvgPrice(100.000, 10, 10.000, 12, 10.500, false);
        assertNotNull($result);
        assertIsArray($result);
        self::assertArrayHasKey('incoming_qty', $result);
        self::assertEquals('226000', $result['inventory_value']);
        print_r($result);
    }


    public function testCalculateAvgCostOutstock()
    {

        $result = $this->calc->calculateAvgPrice(44000.00, 20.0, 2200.00, 10, 10.000, true);
        print_r($result);
        assertIsArray($result);
    }

    public function testInsertMultiple()
    {
        $fistIn = $this->calc->calculateAvgPrice(0, 0, 0, 8, 10000.00, false);
        assertIsArray($fistIn);
        print_r($fistIn);
        self::assertEquals(80000.00, $fistIn['incoming_value']);
        self::assertEquals(80000.00, $fistIn['inventory_value']);
        self::assertEquals(8, $fistIn['qty_on_hand']);
        self::assertEquals(10000.00, $fistIn['avg_cost']);


        $firstTwo = $this->calc->calculateAvgPrice(80000.00, 8, 10000.00, 10, 11000.00, false);
        assertIsArray($firstTwo);
        print_r($firstTwo);
        self::assertEquals(110000.00, $firstTwo['incoming_value']);
        self::assertEquals(190000.00, $firstTwo['inventory_value']);
        self::assertEquals(18, $firstTwo['qty_on_hand']);
        self::assertEquals(10555.56, $firstTwo['avg_cost']);

    }

    public function testZeroAvgCost()
    {
        $rs = $this->calc->calculateAvgPrice(11000.00, 5, 2200.00, 5, 2200.00, true);
        assertIsArray($rs);
        self::assertEquals(0.00, $rs['inventory_value']);
        self::assertEquals(0.00, $rs['qty_on_hand']);
        self::assertEquals(0.00, $rs['avg_cost']);
        print_r($rs);
    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->calc = app()->make(CogsValuationCalc::class);

    }


}
