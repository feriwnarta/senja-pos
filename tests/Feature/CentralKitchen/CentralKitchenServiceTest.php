<?php

namespace Tests\Feature\CentralKitchen;

use App\Contract\CentralKitchen\CentralKitchenRepository;
use App\Contract\CentralKitchen\CentralKitchenService;
use App\Dto\CentralKitchenDTO;
use App\Models\CentralKitchen;
use Mockery\MockInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CentralKitchenServiceTest extends TestCase
{
    private CentralKitchenService $service;


    public function testCreateCentralKitchen()
    {

        $centralKitchen = new CentralKitchen([
            'id' => fake()->uuid(),
            'name' => fake()->name,
        ]);

        $mock = $this->mock(CentralKitchenRepository::class, function (MockInterface $mock) use ($centralKitchen) {
            $mock->shouldReceive('create')->once()->andReturn(
                new CentralKitchen()
            );
        });

        $this->service = new \App\Service\CentralKitchen\CentralKitchenService($mock);
        $result = $this->service->create(new CentralKitchenDTO(
            'code',
            'name'
        ));
        self::assertInstanceOf(CentralKitchen::class, $result);
        self::assertEquals($result['id'], $centralKitchen->id);
        print_r($result);
    }

    public function testFailedCreateCentralKitchen()
    {


        $mock = $this->mock(CentralKitchenRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->never();
        });

        $this->expectException(HttpException::class);

        $this->service = new \App\Service\CentralKitchen\CentralKitchenService($mock);
        $result = $this->service->create(null);

    }


}
