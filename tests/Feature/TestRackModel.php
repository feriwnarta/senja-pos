<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Rack;
use App\Models\Warehouse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class TestRackModel extends TestCase
{

    private Area $areaA;
    private Area $areaB;

    public function testInsertRack()
    {

        $rack = Rack::create([
            'area_id' => $this->areaA->id,
            'name' => 'Rack A1',
            'category_inventory' => 'Bahan Mentah',
        ]);

        self::assertNotNull($rack);

    }


    public function testInsertMultipleRack()
    {
        $rack1 = Rack::create([
            'area_id' => $this->areaA->id,
            'name' => 'Rack A2',
            'category_inventory' => 'Bahan Mentah',
        ]);


        $rack2 = Rack::create([
            'area_id' => $this->areaA->id,
            'name' => 'Rack A3',
            'category_inventory' => 'Bahan Mentah',
        ]);

        assertNotNull($rack1);
        assertNotNull($rack2);
    }


    public function testInsertSameName()
    {
        $this->expectException(QueryException::class);
        
        $rack1 = Rack::create([
            'area_id' => $this->areaA->id,
            'name' => 'Rack A2',
            'category_inventory' => 'Bahan Mentah',
        ]);


        $rack2 = Rack::create([
            'area_id' => $this->areaA->id,
            'name' => 'Rack A2',
            'category_inventory' => 'Bahan Mentah',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('racks')->delete();

        $warehouse = Warehouse::create([
            'warehouse_code' => fake()->uuid(),
            'name' => fake()->name(),
            'address' => fake()->address(),
        ]);

        $this->areaA = Area::create([
            'warehouse_id' => $warehouse->id,
            'name' => 'Area A',
        ]);

        $this->areaB = Area::create([
            'warehouse_id' => $warehouse->id,
            'name' => 'Area A',
        ]);

    }

}
