<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CentralKitchen;
use App\Models\CentralProduction;
use App\Models\Item;
use App\Models\RequestStock;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptRef;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\note;

class WarehouseItemReceiptSeederProduction extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouse = Warehouse::create([
            'id' => fake()->uuid,
            'warehouse_code' => fake()->countryCode,
            'name' => fake()->name,
            'address' => fake()->address,
        ]);

        $requestStock = RequestStock::create([
            'id' => fake()->uuid,
            'warehouses_id' => $warehouse->id,
            'code'=> fake()->countryCode,
            'increment' => 1,
            'note' => fake()->address
        ]);

        $central = CentralKitchen::create([
            'id' => fake()->uuid,
            'code' => fake()->countryCode,
            'name' => fake()->name,
            'address' => fake()->address,
            'phone' => fake()->phoneNumber,
            'email' => fake()->email,
        ]);

        // buat central production
        $production = CentralProduction::create([
            'id' => fake()->uuid,
            'request_stocks_id' => $requestStock->id,
            'central_kitchens_id'=> $central->id,
            'code' => fake()->countryCode,
            'increment' => 1,
        ]);

        $result = $production->reference()->save(new WarehouseItemReceiptRef());

        // buat warheouse item receipt
        $itemReceipt = WarehouseItemReceipt::create([
            'id' => fake()->uuid,
            'warehouse_item_receipt_refs_id' => $result->id,
            'warehouses_id' => $warehouse->id
        ]);

        DB::table('units')->insert([
            'id' => fake()->uuid,
            'code' => 'UNIT01',
            'name' => 'Gram'
        ]);

        DB::table('categories')->insert([
            'id' => fake()->uuid,
            'code' => 'KTG01',
            'name' => 'Bahan mentah'
        ]);

        $item1 = Item::create([
            'code' => fake()->countryCode,
            'name' => fake()->name,
            'units_id' => Unit::first()->id,
            'categories_id' => Category::first()->id,
            'route' => 'PRODUCECENTRALKITCHEN'
        ]);

        $item2 = Item::create([
            'code' => fake()->countryCode,
            'name' => fake()->name,
            'units_id' => Unit::first()->id,
            'categories_id' => Category::first()->id,
            'route' => 'PRODUCECENTRALKITCHEN'
        ]);

        $itemReceipt->details()->createMany([
            [
                'items_id' => $item1->id,
                'qty_send' => 10,
                'qty_accept' => 0,
            ],
            [
                'items_id' => $item2->id,
                'qty_send' => 10,
                'qty_accept' => 0,
            ],
        ]);





    }
}
