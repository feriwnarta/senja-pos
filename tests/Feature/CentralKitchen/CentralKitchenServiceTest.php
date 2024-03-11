<?php

namespace Tests\Feature\CentralKitchen;

use App\Contract\CentralKitchen\CentralKitchenService;
use App\Dto\CentralKitchenDTO;
use App\Http\Resources\CentralKitchenResource;
use App\Models\CentralKitchen;
use App\Repository\CentralKitchen\CentralKitchenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $mock = $this->mock(\App\Contract\CentralKitchen\CentralKitchenRepository::class,function (MockInterface $mock) use ($centralKitchen) {
            $mock->shouldReceive('create')->once()->andReturn(
                new CentralKitchenResource($centralKitchen)
            );
        });

        $this->service = new \App\Service\CentralKitchen\CentralKitchenService($mock);
        $result = $this->service->create(new CentralKitchenDTO(
            'code',
            'name'
        ));
        self::assertInstanceOf(CentralKitchenResource::class, $result);
        self::assertEquals($result['id'], $centralKitchen->id);
        print_r($result);
    }

    public function testFailedCreateCentralKitchen()
    {

        $centralKitchen = new CentralKitchen([
            'id' => fake()->uuid(),
            'name' => fake()->name,
        ]);

        $mock = $this->mock(\App\Contract\CentralKitchen\CentralKitchenRepository::class,function (MockInterface $mock) use ($centralKitchen) {
            $mock->shouldReceive('create')->never();
        });

        $this->expectException(HttpException::class);

        $this->service = new \App\Service\CentralKitchen\CentralKitchenService($mock);
        $result = $this->service->create(null);

    }


}
