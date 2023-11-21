<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class TestItemModel extends TestCase
{
    public function testCursorPaginate()
    {

        $items = Item::orderBy('id')->cursorPaginate(10);
        self::assertNotNull($items);

    }

    public function testSelect()
    {
        $item = Item::first();

        Log::info($item);

        assertNotNull($item);
    }

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('items')->delete();
        Cache::forget('add-warehouse-first-cursor');

        Item::insert(
            [
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
    

            ]
        );
    }


}
