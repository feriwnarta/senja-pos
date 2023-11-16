<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TestCategoryItemModel extends TestCase
{
    public function testCategoryItem()
    {

        $category = CategoryItem::create([
            'category_code' => 'asdadsad',
            'category_name' => 'asd1e3sd',
        ]);

        assertNotNull($category);
    }

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('category_items')->delete();
    }


}
