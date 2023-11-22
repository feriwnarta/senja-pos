<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Unit;
use App\Service\CategoryItemService;
use App\Service\Impl\CategoryItemServiceImpl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertNotNull;

class CategoryItemServiceTest extends TestCase
{

    private CategoryItemService $categoryItemService;

    public function testGetItemCursor()
    {

        $data = $this->categoryItemService->getItemCursor([]);
        assertNotNull($data);
        assertIsArray($data);
        assertIsArray($data['data']);
        Log::info($data);
    }

    public function testSaveCategory()
    {
        $unit = Unit::create([
            'code' => 'CODEBARU',
            'name' => 'BOTOL',
        ]);


        $item1 = Item::create(
            ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
        );

        $item2 = Item::create(
            ['name' => fake()->name(), 'id' => fake()->uuid(), 'item_code' => fake()->currencyCode()],
        );

        $items = [$item1->id, $item2->id];

        $this->assertDatabaseHas('units', [
            'code' => 'CODEBARU',
            'name' => 'BOTOL',
        ]);


        $result = $this->categoryItemService->saveCategory('CATEGORI1', "UNGGASS", $unit->id, $items);
        assertNotNull($result);
        self::assertSame('CATEGORI1', $result->code);
        self::assertSame('UNGGASS', $result->name);

        $this->assertDatabaseHas('categories', [
            'code' => 'CATEGORI1',
            'name' => 'UNGGASS',
        ]);
    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryItemService = app()->make(CategoryItemServiceImpl::class);
        DB::table('categories')->delete();
        DB::table('units')->delete();
        DB::table('categories_items')->delete();
        DB::table('categories_units')->delete();
    }


}
