<?php

namespace Tests\Feature\Compositions;

use App\Dto\CategoryDTO;
use App\Models\Category;
use App\Repository\Compositions\CategoryRepository;
use App\Service\Compositions\CategoryService;
use Exception;
use Mockery\MockInterface;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{


    public function testCreateCategorySuccess()
    {

        $repository = $this->mock(CategoryRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->andReturn(new Category());
        });
        $dto = $this->mock(CategoryDTO::class);

        self::assertNotNull($repository);
        $service = new CategoryService($repository);
        $service->createCategory($dto);

    }

    public function testCreateCategoryFailed()
    {

        $repository = $this->mock(CategoryRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->andThrow(new Exception('kode unit sudah ada'));
        });
        $dto = $this->mock(CategoryDTO::class);

        self::assertNotNull($repository);
        $service = new CategoryService($repository);
        $this->expectException(Exception::class);
        $service->createCategory($dto);

    }


}
