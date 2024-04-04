<?php

namespace Tests\Feature\Compositions;

use App\Contract\Compositions\CategoryRepository;
use App\Dto\CategoryDTO;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryRepositoryTest extends TestCase
{

    use RefreshDatabase;

    private CategoryRepository $repository;
    private CategoryDTO $categoryDTO;

    public function testCreateSuccess()
    {

        $result = $this->repository->create($this->categoryDTO);
        self::assertNotNull($result);
        $this->assertDatabaseHas('categories', [
            'code' => $this->categoryDTO->getCode(),
            'name' => $this->categoryDTO->getName()
        ]);

    }

    public function testCreateFailedCodeSame()
    {
        $result = $this->repository->create($this->categoryDTO);
        self::assertNotNull($result);

        $this->assertDatabaseHas('categories', [
            'code' => $this->categoryDTO->getCode(),
            'name' => $this->categoryDTO->getName()
        ]);

        $this->categoryDTO->setName(fake()->name);
        $this->expectException(Exception::class);
        $result = $this->repository->create($this->categoryDTO);


    }

    public function testCreateFailedNameSame()
    {
        $result = $this->repository->create($this->categoryDTO);
        self::assertNotNull($result);

        $this->assertDatabaseHas('categories', [
            'code' => $this->categoryDTO->getCode(),
            'name' => $this->categoryDTO->getName()
        ]);

        $this->categoryDTO->setCode('asdasdasd');
        $this->expectException(Exception::class);
        $result = $this->repository->create($this->categoryDTO);

    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new \App\Repository\Compositions\CategoryRepository();
        $this->categoryDTO = new CategoryDTO(
            fake()->countryCode,
            fake()->name
        );
    }


}
