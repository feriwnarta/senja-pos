<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Category;
use App\Models\CentralKitchen;
use App\Models\Item;
use App\Models\Rack;
use App\Models\StockItem;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'id' => fake()->uuid,
            'code' => 'KTG01',
            'name' => 'Bahan mentah'
        ]);

        DB::table('categories')->insert([
            'id' => fake()->uuid,
            'code' => 'KTG02',
            'name' => 'Bahan setengah jadi'
        ]);

        DB::table('units')->insert([
            'id' => fake()->uuid,
            'code' => 'UNIT01',
            'name' => 'Gram'
        ]);

        DB::table('units')->insert([
            'id' => fake()->uuid,
            'code' => 'UNIT02',
            'name' => 'Pcs'
        ]);

        $centralKitchen = new CentralKitchen();
        $centralKitchen->code = 'CENTRAL01';
        $centralKitchen->name = 'Central Poris';
        $centralKitchen->phone = fake()->phoneNumber;
        $centralKitchen->address = 'Poris';
        $centralKitchen->email = fake()->email;
        $centralKitchen->save();


        $warehouse = new Warehouse();
        $warehouse->warehouse_code = 'WH0001';
        $warehouse->name = 'Gudang bahan';
        $warehouse->address = 'Jln. Poris';
        $warehouse->save();
        $warehouse->save();

        $warehouse->centralKitchen()->syncWithoutDetaching($centralKitchen->id);

        $area = new Area();
        $area->warehouses_id = $warehouse->id;
        $area->name = 'Area bahan';
        $area->save();

        $rack = new Rack();
        $rack->areas_id = $area->id;
        $rack->name = 'Bahan mentah';
        $rack->save();

        $rack = new Rack();
        $rack->areas_id = $area->id;
        $rack->name = 'Bahan Olahan';
        $rack->save();

        for ($i = 1; $i <= 100; $i++) {
            $item = new Item();
            $item->code = fake()->uuid;
            $item->name = fake()->name;
            $item->units_id = Unit::first()->id;
            $item->categories_id = Category::first()->id;
            $item->route = (rand(0, 1) == 1) ? 'BUY' : 'PRODUCECENTRALKITCHEN';
            $item->save();

            $warehouseItem = $warehouse->warehouseItem()->create([
                'items_id' => $item->id
            ]);

            StockItem::create([
                'warehouse_items_id' => $warehouseItem->id,
                'incoming_qty' => 0,
                'incoming_value' => 0,
                'price_diff' => 0,
                'inventory_value' => 0,
                'qty_on_hand' => 0,
                'avg_cost' => 0,
                'last_cost' => 0,
                'minimum_stock' => 0,
            ]);
        }

    }
}
