<?php

namespace Tests\Feature\Warehouse;

use App\Contract\Warehouse\WarehouseRepository;
use App\Dto\WarehouseDTO;
use App\Dto\WarehouseEnum;
use App\Http\Resources\WarehouseResource;
use App\Models\Area;
use App\Models\Rack;
use App\Models\Warehouse;
use App\Service\Warehouse\WarehouseService;
use Exception;
use Mockery\MockInterface;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertNotNull;

class WarehouseServiceTest extends TestCase
{
    private WarehouseService $warehouseService;
    private WarehouseDTO $warehouseDTO;
    private array $data = [
        [
            'area' => 'Area a',
            'racks' => [
                'A1',
                'A2'
            ]
        ],
        [
            'area' => 'Area b',
            'racks' => [
                'B1'
            ]
        ]
    ];

    public function testGetCreateTypeCentral()
    {
        $mock = $this->mock(WarehouseRepository::class);
        $this->warehouseService = new WarehouseService($mock);
        self::assertNotNull($this->warehouseDTO);
        assertNotNull($this->warehouseService);

        $result = $this->warehouseService->getCreateType($this->warehouseDTO);
        self::assertEquals($result, WarehouseEnum::CENTRAL);
    }

    public function testGetCreateTypeOutlet()
    {
        $mock = $this->mock(WarehouseRepository::class);
        $this->warehouseService = new WarehouseService($mock);
        self::assertNotNull($this->warehouseDTO);
        assertNotNull($this->warehouseService);

        $this->warehouseDTO->setEnum(WarehouseEnum::OUTLET);
        $result = $this->warehouseService->getCreateType($this->warehouseDTO);
        self::assertEquals($result, WarehouseEnum::OUTLET);
    }

    public function testGetCreateTypeOutletWrong()
    {

        $mock = $this->mock(WarehouseRepository::class);
        $this->warehouseService = new WarehouseService($mock);
        self::assertNotNull($this->warehouseDTO);
        assertNotNull($this->warehouseService);

        $this->warehouseDTO->setEnum(WarehouseEnum::OUTLET);
        $result = $this->warehouseService->getCreateType($this->warehouseDTO);
        self::assertNotEquals($result, WarehouseEnum::CENTRAL);
    }

    public function testCreateWarehouse()
    {
        $warehouse = new Warehouse();
        $warehouse->id = fake()->uuid;

        $mock = $this->mock(WarehouseRepository::class, function (MockInterface $mock) use ($warehouse) {

            $mock->shouldReceive('createWarehouse')->once()->andReturn($warehouse);
        });
        $this->warehouseService = new WarehouseService($mock);
        assertNotNull($this->warehouseService);

        $result = $this->warehouseService->createWarehouse($this->warehouseDTO);
        assertEquals($warehouse->id, $result->id);
    }

    public function testCreateWarehouseCentral()
    {

        $warehouse = new Warehouse();
        $warehouse->id = fake()->uuid;

        $mock = $this->mock(WarehouseRepository::class, function (MockInterface $mock) use ($warehouse) {
            $mock->shouldReceive('createRelationCentral')->once()->andReturn([]);
        });

        $this->warehouseService = new WarehouseService($mock);
        assertNotNull($this->warehouseService);

        $result = $this->warehouseService->createWarehouseCentral($warehouse, $this->warehouseDTO);
        assertIsArray($result);

    }

    public function testCreateWarehouseOutlet()
    {
        $warehouse = new Warehouse();
        $warehouse->id = fake()->uuid;

        $mock = $this->mock(WarehouseRepository::class, function (MockInterface $mock) use ($warehouse) {
            $mock->shouldReceive('createRelationCentral')->once()->andReturn([]);
        });

        $this->warehouseDTO->setEnum(WarehouseEnum::OUTLET);
        $this->warehouseService = new WarehouseService($mock);
        assertNotNull($this->warehouseService);

        $result = $this->warehouseService->createWarehouseCentral($warehouse, $this->warehouseDTO);
        assertIsArray($result);
    }

    public function testCreateAreaAndRack()
    {
        $area = new Area();
        $area->id = fake()->uuid;
        $area->name = fake()->name;

        $rack = new Rack();
        $rack->id = fake()->uuid;


        $mock = $this->mock(WarehouseRepository::class, function (MockInterface $mock) use ($area, $rack) {
            $mock->shouldReceive('createAreas')->twice()->andReturn($area);
            $mock->shouldReceive('createRacks')->times(3)->andReturn($rack);
        });

        $this->warehouseService = new WarehouseService($mock);
        $this->warehouseDTO->setId(fake()->uuid);

        $this->warehouseService->createAreaAndRack($this->warehouseDTO);
    }

    public function testCreateAreaAnRackZeo()
    {
        $area = new Area();
        $area->id = fake()->uuid;
        $area->name = fake()->name;

        $rack = new Rack();
        $rack->id = fake()->uuid;


        $mock = $this->mock(WarehouseRepository::class, function (MockInterface $mock) use ($area, $rack) {
            $mock->shouldReceive('createAreas')->never()->andReturn($area);
            $mock->shouldReceive('createRacks')->never()->andReturn($rack);
        });

        $this->warehouseDTO->setAreasAndRack([]);

        $this->warehouseService = new WarehouseService($mock);
        $this->warehouseDTO->setId(fake()->uuid);
        $this->warehouseService->createAreaAndRack($this->warehouseDTO);
    }

    public function testCreateFullCentralWarehouse()
    {
        $area = new Area();
        $area->id = fake()->uuid;

        $rack = new Rack();
        $rack->id = fake()->uuid;

        $warehouse = new Warehouse();
        $warehouse->id = fake()->uuid;

        $mock = $this->mock(WarehouseRepository::class, function (MockInterface $mock) use ($area, $rack, $warehouse) {
            $mock->shouldReceive('createWarehouse')->once()->andReturn($warehouse);
            $mock->shouldReceive('createAreas')->twice()->andReturn($area);
            $mock->shouldReceive('createRacks')->times(3)->andReturn($rack);
            $mock->shouldReceive('createRelationCentral')->once()->andReturn([]);
        });

        $this->warehouseService = new WarehouseService($mock);
        $result = $this->warehouseService->create($this->warehouseDTO);
        self::assertInstanceOf(WarehouseResource::class, $result);

    }

    public function testCreateFullCentralWarehouseWithoutRack()
    {
        $area = new Area();
        $area->id = fake()->uuid;

        $rack = new Rack();
        $rack->id = fake()->uuid;

        $warehouse = new Warehouse();
        $warehouse->id = fake()->uuid;

        $mock = $this->mock(WarehouseRepository::class, function (MockInterface $mock) use ($area, $rack, $warehouse) {
            $mock->shouldReceive('createWarehouse')->once()->andReturn($warehouse);
            $mock->shouldReceive('createAreas')->twice()->andReturn($area);
            $mock->shouldReceive('createRacks')->times(3)->andReturn($rack);
            $mock->shouldReceive('createRelationCentral')->once()->andReturn([]);
        });

        $this->warehouseService = new WarehouseService($mock);
        $dto = new WarehouseDTO(
            fake()->uuid,
            fake()->countryCode,
            fake()->name,
            [],
            '',
            WarehouseEnum::CENTRAL
        );
        $dto->setAreasAndRack($this->data);
        $result = $this->warehouseService->create($dto);
        self::assertInstanceOf(WarehouseResource::class, $result);

    }

    public function testFailedCreateCentralWarehouse()
    {
        $mock = $this->mock(WarehouseRepository::class);

        $this->warehouseService = new WarehouseService($mock);
        $this->expectException(Exception::class);
        $result = $this->warehouseService->create(null);

    }

    public function testCreateWarehouseCentralWithoutAddress()
    {
        $area = new Area();
        $area->id = fake()->uuid;

        $rack = new Rack();
        $rack->id = fake()->uuid;

        $warehouse = new Warehouse();
        $warehouse->id = fake()->uuid;

        $mock = $this->mock(WarehouseRepository::class, function (MockInterface $mock) use ($area, $rack, $warehouse) {
            $mock->shouldReceive('createWarehouse')->once()->andReturn($warehouse);
            $mock->shouldReceive('createAreas')->never()->andReturn($area);
            $mock->shouldReceive('createRacks')->never()->andReturn($rack);
            $mock->shouldReceive('createRelationCentral')->once()->andReturn([]);
        });

        $this->warehouseService = new WarehouseService($mock);
        $result = $this->warehouseService->create(new WarehouseDTO(
            fake()->uuid,
            fake()->countryCode,
            fake()->name,
            [],
            '',
            WarehouseEnum::CENTRAL
        ));
        self::assertInstanceOf(WarehouseResource::class, $result);

    }

    protected function setUp(): void
    {
        parent::setUp();


        $this->warehouseDTO = new WarehouseDTO(
            fake()->uuid,
            fake()->countryCode(),
            fake()->name,
            $this->data,
            '',
            WarehouseEnum::CENTRAL
        );


    }


}
