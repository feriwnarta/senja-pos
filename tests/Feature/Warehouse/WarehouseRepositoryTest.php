<?php

namespace Tests\Feature\Warehouse;

use App\Contract\Warehouse\WarehouseRepository;
use App\Dto\WarehouseDTO;
use App\Dto\WarehouseEnum;
use App\Models\Area;
use App\Models\CentralKitchen;
use App\Models\Outlet;
use App\Models\Warehouse;
use App\Repository\CentralKitchen\CentralKitchenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertNotNull;

class WarehouseRepositoryTest extends TestCase
{

    use RefreshDatabase;

    private WarehouseRepository $repository;

    private WarehouseDTO $warehouseDTO;

    protected function setUp():void
    {
        parent::setUp();
        $this->repository = new \App\Repository\Warehuouse\WarehouseRepository();

        $this->warehouseDTO = new WarehouseDTO(
            fake()->uuid(),
            fake()->countryCode,
            fake()->name,
            [],
            '',
            WarehouseEnum::CENTRAL
        );
    }

    public function testCreateWarehouse()
    {

        $warehouse = $this->repository->createWarehouse($this->warehouseDTO);
        assertNotNull($warehouse);
        self::assertInstanceOf(Warehouse::class, $warehouse);
        self::assertEquals($this->warehouseDTO->getCode(), $warehouse->warehouse_code);

        $this->assertDatabaseHas('warehouses', [
            'warehouse_code' => $this->warehouseDTO->getCode(),
            'name'  => $this->warehouseDTO->getName()
        ]);

    }

    public function testCreateWarehouseFailedBecauseNoCode()
    {

        $this->warehouseDTO->setCode(null);

        $this->expectException(\Exception::class);
        $warehouse = $this->repository->createWarehouse($this->warehouseDTO);
        self::assertNull($warehouse);

        $this->assertDatabaseMissing('warehouses', [
            'name' => $this->warehouseDTO->getName()
        ]);

    }

    public function testCreateRelationCentralSuccess()
    {

        $central = new CentralKitchen();
        $central->code = fake()->countryCode;
        $central->name = fake()->name;
        $central->address = '';
        $central->save();

        $this->warehouseDTO->setSourceId($central->id);

        $warehouse = $this->repository->createWarehouse($this->warehouseDTO);
        $result = $this->repository->createRelationCentral($warehouse, $this->warehouseDTO);
        assertIsArray($result);

        $this->assertDatabaseHas('warehouses_central_kitchens', [
            'warehouses_id' => $warehouse->id,
            'central_kitchens_id' => $central->id
        ]);

    }

    public function testCreateRelationCentralFailedSourceId()
    {

        $this->expectException(\Exception::class);
        $warehouse = $this->repository->createWarehouse($this->warehouseDTO);
        $result = $this->repository->createRelationCentral($warehouse, $this->warehouseDTO);
        assertIsArray($result);

        $this->assertDatabaseMissing('warehouses_central_kitchens', [
            'warehouses_id' => $warehouse->id
        ]);

    }

    public function testCreateRelationOutletSuccess()
    {

        $central = new CentralKitchen();
        $central->code = fake()->countryCode;
        $central->name = fake()->name;
        $central->address = '';
        $central->save();

        $outlet = new Outlet();
        $outlet->central_kitchens_id = $central->id;
        $outlet->code = fake()->countryCode();
        $outlet->name = fake()->name();
        $outlet->address = fake()->address();
        $outlet->save();

        $this->warehouseDTO->setSourceId($outlet->id);
        $warehouse = $this->repository->createWarehouse($this->warehouseDTO);
        $result = $this->repository->createRelationOutlet($warehouse, $this->warehouseDTO);
        assertIsArray($result);

        $this->assertDatabaseHas('warehouses_outlets', [
            'warehouses_id' => $warehouse->id,
            'outlets_id' => $outlet->id
        ]);
    }

    public function testCreateRelationOutletFailedSourceId()
    {

        $central = new CentralKitchen();
        $central->code = fake()->countryCode;
        $central->name = fake()->name;
        $central->address = '';
        $central->save();

        $outlet = new Outlet();
        $outlet->central_kitchens_id = $central->id;
        $outlet->code = fake()->countryCode();
        $outlet->name = fake()->name();
        $outlet->address = fake()->address();
        $outlet->save();


        $this->expectException(\Exception::class);
        $warehouse = $this->repository->createWarehouse($this->warehouseDTO);
        $result = $this->repository->createRelationOutlet($warehouse, $this->warehouseDTO);

        $this->assertDatabaseMissing('warehouses_outlets', [
            'warehouses_id' => $warehouse->id
        ]);

    }

    public function testCreateAreaSuccess()
    {
        $warehouse = $this->repository->createWarehouse($this->warehouseDTO);
        $result = $this->repository->createAreas($warehouse->id, "area 1");
        assertNotNull($result);
        self::assertInstanceOf(Area::class, $result);

        $this->assertDatabaseHas('areas', [
            'warehouses_id' => $warehouse->id,
            'name'=> 'area 1'
        ]);

    }

    public function testCreateAreaFailed()
    {
        $this->expectException(\Exception::class);
        $result = $this->repository->createAreas(fake()->uuid, "area 1");
    }


    public function testCreateRacksSuccess() {

        $warehouse = $this->repository->createWarehouse($this->warehouseDTO);
        $result = $this->repository->createAreas($warehouse->id, "area 1");
        assertNotNull($result);
        self::assertInstanceOf(Area::class, $result);

        $result1 = $this->repository->createRacks($result->id, 'Rak 1');
        $result2 = $this->repository->createRacks($result->id, 'Rak 2');

        assertNotNull($result1);
        assertNotNull($result2);

        $this->assertDatabaseHas('racks', [
            'name' => 'Rak 1'
        ]);
        $this->assertDatabaseHas('racks', [
            'name' => 'Rak 2'
        ]);
    }

    public function testCreateRacksFailed()
    {
        $this->expectException(\Exception::class);
        $result = $this->repository->createRacks(fake()->uuid, "area 1");
    }

}
