<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Warehouse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TestAreaModel extends TestCase
{

    private Warehouse $warehouse;

    public function testInsertArea()
    {

        $area = Area::create([
            'warehouses_id' => $this->warehouse->id,
            'name' => 'Area A',
        ]);

        self::assertNotNull($area);
        self::assertSame('Area A', $area->name);
    }


    public function testInsertWarehouseArea()
    {
        // lakukan proses simpan gudang
        $warehouse = Warehouse::create(
            [
                'warehouse_code' => fake()->countryCode(),
                'name' => fake()->name(),
                'address' => fake()->address(),
            ]
        );

        $warehouse->areas()->create(
            ['name' => 'A1']
        );

        $warehouse->areas()->create(
            ['name' => 'A2']
        );
        $warehouse->areas()->create(
            ['name' => 'A3']
        );

    }


    /**
     * berharap mendapatkan query exceptions saat mengisi warehouse id dengan id asal asalan
     * @return void
     */
    public function testWrongWarehouseId()
    {

        $this->expectException(QueryException::class);

        $area = Area::create([
            'warehouse_id' => 'wrong id',
            'name' => 'Area A',
        ]);

    }

    // test nama diisi dengan null
    public function testNameNull()
    {
        $this->expectException(QueryException::class);
        $area = Area::create([
            'warehouse_id' => 'wrong id',
        ]);
    }


    protected function setUp(): void
    {
        parent::setUp();

        DB::table('warehouses')->delete();

        $this->warehouse = Warehouse::create([
            'warehouse_code' => fake()->uuid(),
            'name' => fake()->name(),
            'address' => fake()->address(),
        ]);
    }

}
