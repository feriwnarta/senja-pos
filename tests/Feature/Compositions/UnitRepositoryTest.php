<?php

namespace Tests\Feature\Compositions;

use App\Contract\Compositions\UnitRepository;
use App\Dto\UnitDTO;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class UnitRepositoryTest extends TestCase
{

    private UnitRepository $repository;
    private UnitDTO $unitDTO;

    public function testCreateSuccess()
    {
        $result = $this->repository->create($this->unitDTO);
        assertNotNull($result);
        $this->assertDatabaseHas('units', [
            'code' => $this->unitDTO->getCode(),
            'name' => $this->unitDTO->getName()
        ]);

    }

    public function testCreateFailedWhenCodeSame()
    {
        $result = $this->repository->create($this->unitDTO);
        assertNotNull($result);
        $this->assertDatabaseHas('units', [
            'code' => $this->unitDTO->getCode(),
            'name' => $this->unitDTO->getName()
        ]);


        $this->expectException(Exception::class);
        $result = $this->repository->create(new UnitDTO(
            $this->unitDTO->getCode(),
            fake()->name,
        ));
        assertNotNull($result);
    }

    public function testCreateFailedWhenNameSame()
    {
        $result = $this->repository->create($this->unitDTO);
        assertNotNull($result);
        $this->assertDatabaseHas('units', [
            'code' => $this->unitDTO->getCode(),
            'name' => $this->unitDTO->getName()
        ]);


        $this->expectException(Exception::class);
        $result = $this->repository->create(new UnitDTO(
            fake()->countryCode,
            $this->unitDTO->getName(),
        ));
        assertNotNull($result);
    }


    protected function setUp(): void
    {

        parent::setUp();
        $this->repository = new \App\Repository\Compositions\UnitRepository();
        $this->unitDTO = new UnitDTO(fake()->countryCode, fake()->name);
    }

    use RefreshDatabase;
}
