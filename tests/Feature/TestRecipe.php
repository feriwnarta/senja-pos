<?php

namespace Tests\Feature;

use App\Service\Impl\RecipeServiceImpl;
use App\Service\RecipeService;
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


    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeService = app()->make(RecipeServiceImpl::class);
    }


}
