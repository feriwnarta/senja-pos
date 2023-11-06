<?php

namespace Tests\Feature;

use App\Models\Warehouse;
use Illuminate\Database\UniqueConstraintViolationException;
use Tests\TestCase;

class TestWarehouseModel extends TestCase
{
    private Warehouse $warehouse;

    /**
     * test insert warehouse baru
     * @return void
     */
    public function testInsertModel(): void
    {
        self::assertNotNull($this->warehouse);
        self::assertIsString($this->warehouse->name);
    }


    /**
     * Test insert warehouse baru dengan code sama
     * berharap mendapatkan error unique constraint
     * @return void
     */
    public function testInsertSameCodeWarehouse()
    {

        $this->expectException(UniqueConstraintViolationException::class);

        $warehouseSample = Warehouse::create([
            'warehouse_code' => 'BMDGUI01',
            'name' => 'Gudang pusat',
            'address' => fake()->address(),
        ]);


        $warehouseTest = Warehouse::create([
            'warehouse_code' => $warehouseSample->warehouse_code,
            'name' => 'Gudang pusat',
            'address' => fake()->address(),
        ]);


    }

    public function testInsertWarehouse()
    {
        Warehouse::insert([
            ['id' => fake()->uuid(), 'warehouse_code' => fake()->countryCode(), 'name' => fake()->name(), 'address' => fake()->address()],
            ['id' => fake()->uuid(), 'warehouse_code' => fake()->countryCode(), 'name' => fake()->name(), 'address' => fake()->address()],
            ['id' => fake()->uuid(), 'warehouse_code' => fake()->countryCode(), 'name' => fake()->name(), 'address' => fake()->address()],
            ['id' => fake()->uuid(), 'warehouse_code' => fake()->countryCode(), 'name' => fake()->name(), 'address' => fake()->address()],
            ['id' => fake()->uuid(), 'warehouse_code' => fake()->countryCode(), 'name' => fake()->name(), 'address' => fake()->address()],
            ['id' => fake()->uuid(), 'warehouse_code' => fake()->countryCode(), 'name' => fake()->name(), 'address' => fake()->address()],
            ['id' => fake()->uuid(), 'warehouse_code' => fake()->countryCode(), 'name' => fake()->name(), 'address' => fake()->address()],
        ]);
    }


    /**
     * test berharap mendapatkan exception unique constraint saat mengisi nama yang duplicate
     * @return void
     */
    public function testDuplicateName(): void
    {
        $this->expectException(UniqueConstraintViolationException::class);

        $warehouseSample = Warehouse::create([
            'warehouse_code' => 'BMDGUI01',
            'name' => 'Gudang pusat',
            'address' => fake()->address(),
        ]);


        $warehouseTest = Warehouse::create([
            'warehouse_code' => 'BMDGUI02',
            'name' => 'Gudang pusat',
            'address' => fake()->address(),
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->warehouse = Warehouse::factory()->create();
//        DB::table('warehouses')->delete();
    }


}
