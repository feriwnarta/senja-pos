<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Item;
use App\Models\Rack;
use App\Models\Warehouse;
use App\Service\WarehouseService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;

class WarehouseServiceTest extends TestCase
{
    private WarehouseService $warehouseService;
    private Warehouse $warehouse;

    /**
     * test fungsi detail warehouse
     * @return void
     */
    public function testGetDetailWarehouse()
    {
        self::assertNotNull($this->warehouse);
        self::assertSame($this->warehouse->id, $this->warehouse->id);


        $result = $this->warehouseService->getDetailWarehouse($this->warehouse->id);

        assertNotNull($result);
        self::assertSame($result->id, $this->warehouse->id);
    }

    public function testGetItemNotYetAddedRackCursor()
    {

        $result = $this->warehouseService->getItemNotYetAddedRackCursor();
        Log::info($result);
        assertNotNull($result);
        assertIsArray($result['data']);


    }


    /**
     * test fungsi detail warehouse jika id nya tidak ditemukan
     * dan berharap execption dengan code 1
     * @return void
     */
    public function testExceptionIfNullGetDetailWarehouse()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('detail warehouse tidak ditemukan karena data null testExceptionIfNullGetDetailWarehouse');
        $result = $this->warehouseService->getDetailWarehouse('1');
    }

    /**
     * test dapatkan detail data areas, racks dan item dari warehouse
     * @return void
     */
    public function testGetDetailDataAreaRackItemWarehouseSuccess()
    {

        $area = Area::factory()->create([
            'id' => fake()->uuid(),
            'warehouses_id' => $this->warehouse->id,
            'name' => fake()->name(),
        ]);


        $racks = Rack::factory()->create([
            'id' => fake()->uuid(),
            'areas_id' => $area->id,
            'name' => fake()->name(),
            'category_inventory' => 'Bahan mentah'
        ]);

        $racks2 = Rack::factory()->create([
            'id' => fake()->uuid(),
            'areas_id' => $area->id,
            'name' => fake()->name(),
            'category_inventory' => 'Bahan 1/2 jadi'
        ]);

        $item = Item::factory()->create([
            'racks_id' => $racks->id,
            'name' => fake()->name(),
        ]);

        $item = Item::factory()->create([
            'racks_id' => $racks2->id,
            'name' => fake()->name() . '2',
        ]);

        $item = Item::factory()->create([
            'racks_id' => $racks2->id,
            'name' => fake()->name() . '3',
        ]);


        $data = $this->warehouseService->getDetailDataAreaRackItemWarehouse($this->warehouse);

        assertNotNull($data);
        self::assertNotEmpty($data);
        Log::debug(json_encode($data, JSON_PRETTY_PRINT));
    }


    /**
     * test fungsi getDetailDataAreaRackItemWarehouse jika warheouse tidak ditemukan
     * dan akan mengeluarkan exception null dengan code 2
     * @return void
     */
    public function testExceptionIfParamGetDataAreaRackItemWarehouseNull()
    {
        $this->expectException(\Exception::class);

        $data = $this->warehouseService->getDetailDataAreaRackItemWarehouse(Warehouse::find('1'));
        $this->expectExceptionCode(2);

    }

    /**
     *  test fungsi getDetailDataAreaRackItemWarehouse jika area null
     * @return void
     */
    public function testGetDetailDataAreaRackItemWarehouseAreaNull()
    {
        $data = $this->warehouseService->getDetailDataAreaRackItemWarehouse($this->warehouse);

        assertNotNull($data);
        self::assertEmpty($data);
    }


    public function testGetDetailDataAreaRackItemWarehouseRackNull()
    {
        $area = Area::factory()->create([
            'id' => fake()->uuid(),
            'warehouses_id' => $this->warehouse->id,
            'name' => fake()->name(),
        ]);

        $data = $this->warehouseService->getDetailDataAreaRackItemWarehouse($this->warehouse);
        assertNotNull($data);
        assertNotEmpty($data);
        Log::debug($data);
    }

    public function testGetDataAreaRackItemWarehouseDoubleArea()
    {
        $area = Area::factory()->create([
            'id' => fake()->uuid(),
            'warehouses_id' => $this->warehouse->id,
            'name' => fake()->name(),
        ]);

        $area2 = Area::factory()->create([
            'id' => fake()->uuid(),
            'warehouses_id' => $this->warehouse->id,
            'name' => fake()->name(),
        ]);


        $racks = Rack::factory()->create([
            'id' => fake()->uuid(),
            'areas_id' => $area->id,
            'name' => fake()->name(),
            'category_inventory' => 'Bahan mentah'
        ]);

        $racks2 = Rack::factory()->create([
            'id' => fake()->uuid(),
            'areas_id' => $area2->id,
            'name' => fake()->name(),
            'category_inventory' => 'Bahan 1/2 jdi'
        ]);


        $data = $this->warehouseService->getDetailDataAreaRackItemWarehouse($this->warehouse);

        assertNotNull($data);
        self::assertNotEmpty($data);
        Log::debug(json_encode($data, JSON_PRETTY_PRINT));
    }

    public function testAddRack()
    {

        $area = Area::factory()->create([
            'id' => fake()->uuid(),
            'warehouses_id' => $this->warehouse->id,
            'name' => fake()->name(),
        ]);

        $result = $this->warehouseService->addNewRack($area->id);
        assertNotNull($result);

    }

    public function testAddRackFailed()
    {


        $result = $this->warehouseService->addNewRack('asd');
        self::assertNull($result);


    }

    public function testGetRacks()
    {

        $racks = Rack::where('areas_id', '9a9c14b3-90cb-4879-b928-2e5b02e167e4')->get();

        assertNotNull($racks);
        Log::debug('test get rack');
        Log::debug($racks);

    }

    public function testSaveWarehouse()
    {
        $sample = [
            [
                'area' => [
                    'id' => '9a9df812-0bc4-4b3f-9626-7de117966307',
                    'area' => 'Area Lengkap',
                    'racks' => [
                        [
                            'id' => '9a9df812-0c52-4cd9-ac1a-76351a0a720e',
                            'name' => 'A1',
                            'category_inventory' => 'Bahan mentah',
                            'item' => [],
                        ],
                    ],
                ],
            ],
        ];


        $result = $this->warehouseService->saveWarehouse($sample);

        assertNotNull($result);
        self::assertTrue($result);
    }

    public function testEditWithOneAreaTwoRacks()
    {
        $data = [
            [
                'area' => [
                    'id' => '9a9df812-0bc4-4b3f-9626-7de117966307',
                    'area' => 'Area Lengkap',
                    'racks' => [
                        [
                            'id' => '9a9df812-0c52-4cd9-ac1a-76351a0a720e',
                            'name' => 'A1',
                            'category_inventory' => 'Bahan mentah',
                            'item' => [],
                        ],
                        [
                            'id' => '9a9dfc70-01f8-46cf-afb2-9b64fce292b5',
                            'name' => 'A3',
                            'category_inventory' => 'Bahan 1/2 jadi',
                            'item' => [],
                        ],
                    ],
                ],
            ],
        ];

        $result = $this->warehouseService->saveWarehouse($data);

        assertNotNull($result);
        self::assertTrue($result);

    }

    public function testEditWithDoubleArea()
    {
        $data = [
            [
                'area' => [
                    'id' => '9a9df812-0bc4-4b3f-9626-7de117966307',
                    'area' => 'Area Lengkap',
                    'racks' => [
                        [
                            'id' => '9a9df812-0c52-4cd9-ac1a-76351a0a720e',
                            'name' => 'A1',
                            'category_inventory' => 'Bahan mentah',
                            'item' => [],
                        ],
                        [
                            'id' => '9a9dfc70-01f8-46cf-afb2-9b64fce292b5',
                            'name' => 'A3',
                            'category_inventory' => 'Bahan 1/2 jadi',
                            'item' => [],
                        ],
                    ],
                ],
            ],
            [
                'area' => [
                    'id' => '9a9dfd5d-480b-44d3-b367-1d5cdd0ac0e8',
                    'area' => 'Area bahan',
                    'racks' => [
                        [
                            'id' => '9a9dfd5d-4907-45cd-9f75-05dd5d6fac64',
                            'name' => 'AB1',
                            'category_inventory' => 'Bahan mentah',
                            'item' => [],
                        ],
                    ],
                ],
            ],
        ];
        $result = $this->warehouseService->saveWarehouse($data);

        assertNotNull($result);
        self::assertTrue($result);

    }


    public function testSaveWarehouseDataEmpty()
    {
        $this->expectException(\Exception::class);
        $result = $this->warehouseService->saveWarehouse([]);
        $this->expectExceptionMessage('Gagal melakukan update warehouse parameter kosong');
    }

    public function testSaveWarehouseMustException()
    {
        $sample = [
            [
                "area" => [
                    "id" => "kosong",
                    "area" => "Area Bahan",


                ]
            ],
            [
                "area" => [
                    "id" => "kosong",
                    "area" => "Area Pelengkap",

                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $result = $this->warehouseService->saveWarehouse($sample);
        $this->expectExceptionMessage('Gagal melakukan update warehouse data area kosong');


    }


    public function testGetItemRackByIdWithCursorWhenIdEmpty()
    {
        $racks = $this->warehouseService->getItemRackByIdWithCursor('asdsad');
        self::assertEmpty($racks['data']);
    }

    public function testGetItemRackAddedByIdWithCursor()
    {

        $data = $this->warehouseService->getItemRackAddedByIdWithCursor('9a91a5c0-289c-44cb-b37b-bb91d6cfad8b');
        assertNotNull($data);
        self::assertIsArray($data['data']);
        Log::info($data);
    }

    public function testManipulateItemRackAdded()
    {
        $id = '9a91a5c0-289c-44cb-b37b-bb91d6cfad8b';
        $data = $this->warehouseService->getItemRackAddedByIdWithCursor($id);
        assertNotNull($data);

        $result = $this->warehouseService->manipulateItemRackAdded($data['data'], $id);
        assertNotNull($result);
        assertIsArray($result);
        self::assertIsString($result[0]['id']);

        Log::info($result);

    }

    public function testNextCursorItemRackAddedById()
    {
        $id = '9a91a5c0-289c-44cb-b37b-bb91d6cfad8b';
        $data = $this->warehouseService->getItemRackAddedByIdWithCursor($id);
        assertNotNull($data);

        $result = $this->warehouseService->nextCursorItemRackAddedById($id, $data['next_cursor']);
        assertNotNull($result);
        assertIsArray($result['data']);

        Log::info("nextCursorItemRackAddedById");
        Log::info($result);

    }


    protected function setUp(): void
    {
        parent::setUp();

//        DB::table('items')->delete();
//        DB::table('racks')->delete();
//        DB::table('areas')->delete();
//        DB::table('warehouses')->delete();
//
////
        $this->warehouseService = $this->app->make(WarehouseService::class);
////
//        $this->warehouse = Warehouse::factory()->create(
//            [
//                'id' => fake()->uuid(),
//                'warehouse_code' => fake()->countryCode(),
//                'name' => fake()->name(),
//                'address' => fake()->address()
//            ]
//        );


    }


}
