<?php

namespace Tests\Feature\Compositions;

use App\Repository\Compositions\RecipeRepository;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RecipeRepositoryTest extends TestCase
{
    private \App\Contract\Compositions\RecipeRepository $recipeRepository;

    public function testShowRecipeTypeMaterial()
    {
        $result = $this->recipeRepository->showRecipeTypeMaterial();
        self::assertNotNull($result);
        Log::info($result);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeRepository = new RecipeRepository();
    }


}
