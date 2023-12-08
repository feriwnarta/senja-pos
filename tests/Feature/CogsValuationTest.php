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
        $avgCost = 12.000;

        $result = $this->calc->initialAvg($stockAwal, $avgCost);
        assertNotNull($result);
        self::assertIsArray($result);
        self::assertArrayHasKey('inventory_value', $result);
        self::assertArrayHasKey('incoming_value', $result);
        print_r($result);

    }

    public function testCalculateAvgCostInstock()
    {

        $result = $this->calc->calculateAvgPrice(100.000, 10, 10.000, 12, 10.500);
        assertNotNull($result);
        assertIsArray($result);
        self::assertArrayHasKey('incoming_qty', $result);
        self::assertEquals('226.000', $result['inventory_value']);
        print_r($result);
    }


    public function testCalculateAvgCostOutstock()
    {

        $result = $this->calc->calculateAvgPrice(380.500, 37, 12.000, -10, 10.000);
        assertIsArray($result);
    }

    public function testInsertMultiple()
    {
        $fistIn = $this->calc->calculateAvgPrice(0, 0, 0, 8, 10.000);
        assertIsArray($fistIn);
        print_r($fistIn);
        self::assertEquals(80.000, $fistIn['incoming_value']);
        self::assertEquals(80.000, $fistIn['inventory_value']);
        self::assertEquals(8, $fistIn['qty_on_hand']);
        self::assertEquals(10.000, $fistIn['avg_cost']);


        $firstTwo = $this->calc->calculateAvgPrice(80.000, 8, 10.000, 10, 11.000);
        assertIsArray($firstTwo);
        print_r($firstTwo);
        self::assertEquals(110.000, $firstTwo['incoming_value']);
        self::assertEquals(190.000, $firstTwo['inventory_value']);
        self::assertEquals(18, $firstTwo['qty_on_hand']);
        self::assertEquals(10.556, $firstTwo['avg_cost']);

    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->calc = app()->make(CogsValuationCalc::class);

    }


}
