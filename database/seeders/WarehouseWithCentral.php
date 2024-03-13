<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\CentralKitchen;
use App\Models\Rack;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseWithCentral extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

    }
}
