<?php

namespace Tests\Feature\Compositions;

use App\Contract\Compositions\UnitRepository;
use App\Dto\UnitDTO;
use App\Models\Unit;
use App\Service\Compositions\UnitService;
use Exception;
use Mockery\MockInterface;
use Tests\TestCase;

class UnitServiceTest extends TestCase
{
    public function testCreateNewUnitSuccess()
    {

        $repositoryMock = $this->mock(UnitRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->once()->andReturn(new Unit());
        });

        $service = new UnitService($repositoryMock);
        $service->create(new UnitDTO(
            fake()->countryCode(),
            fake()->name,
        ));

        self::assertNotNull($service);
        self::assertNotNull($repositoryMock);
    }

    public function testCreateNewUnitFailed()
    {

        $repositoryMock = $this->mock(UnitRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->once()->andThrow(new Exception('unit code sudah ada'));
        });
        $service = new UnitService($repositoryMock);
        $this->expectException(Exception::class);
        $dto = $this->getMockBuilder(UnitDTO::class)->disableOriginalConstructor()->getMock();
        $service->create($dto);
    }

}
