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
//
        $this->warehouseService = $this->app->make(WarehouseService::class);
//
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
