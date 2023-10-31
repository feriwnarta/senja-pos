<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TestItemModel extends TestCase
{
    public function testCursorPaginate()
    {


        $items = Item::orderBy('id')->cursorPaginate(10);


        self::assertNotNull($items);

    }

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('items')->delete();

        Item::insert(
            [
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()], ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()], ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()], ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()], ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()], ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()], ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()], ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],
                ['name' => fake()->name(), 'id' => fake()->uuid()],

            ]
        );
    }


}
