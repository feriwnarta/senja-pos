<?php

namespace Tests\Feature;

use App\Service\Impl\RecipeServiceImpl;
use App\Service\RecipeService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class TestRecipe extends TestCase
{

    private RecipeService $recipeService;

    public function testSelectItemMaterial()
    {

        $result = $this->recipeService->selectMenuOrMaterial(false);

        assertNotNull($result);

    }

    public function testSaveRecipeItem()
    {

        DB::table('recipe_items')->delete();
        DB::table('recipe_item_details')->delete();


        $this->recipeService->saveRecipeItem('RECIPE01', '9ad1ee71-1db7-46aa-a1f8-9901f1ac6a96', [
            [
                'items_id' => '9ad1e612-80fb-4096-ae06-0f8bec1b9506',
                'units_id' => '9ad1e5d4-74d2-44a8-b215-816d135c6115',
                'usage' => '1'
            ]
        ]);

        $this->assertDatabaseCount('recipe_items', 1);
    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeService = app()->make(RecipeServiceImpl::class);
    }


}
