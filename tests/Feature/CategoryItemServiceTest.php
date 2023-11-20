<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class CategoryItemServiceTest extends TestCase
{
    public function testInsertCategory()
    {

        $category = Category::create([
            'code' => 'CATEGORY01',
            'name' => 'NAME 01'
        ]);

        self::assertNotNull($category);
        self::assertEquals($category->name, 'NAME 01');

        $item = Item::create([
            'item_code' => 'ITEM01',
            'item_image' => 'ITEM IMAGE',
            'name' => 'Item baru'
        ]);

        assertNotNull($item);

        $category->items()->attach($item->id);
        assertNotNull($category);

    }

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('categories')->delete();
        DB::table('items')->delete();
    }


}
