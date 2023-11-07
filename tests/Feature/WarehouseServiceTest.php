<?php

namespace Tests\Feature;

use App\Models\Warehouse;
use App\Service\WarehouseService;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class WarehouseServiceTest extends TestCase
{
    private WarehouseService $warehouseService;
    private Warehouse $warehouse;

    public function testGetDetailWarehouse()
    {


        self::assertNotNull($this->warehouse);
        self::assertSame($this->warehouse->id, $this->warehouse->id);


        $result = $this->warehouseService->getDetailWarehouse($this->warehouse->id);

        assertNotNull($result);
        self::assertSame($result->id, $this->warehouse->id);
        
    }

    public function testExceptionIfNullGetDetailWarehouse()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('warehouse not found by id : 1');
        $result = $this->warehouseService->getDetailWarehouse('1');
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->warehouseService = $this->app->make(WarehouseService::class);

        $this->warehouse = Warehouse::factory()->create(
            [
                'id' => fake()->uuid(),
                'warehouse_code' => fake()->countryCode(),
                'name' => fake()->name(),
                'address' => fake()->address()
            ]
        );
    }


}
