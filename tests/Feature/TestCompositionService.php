<?php

namespace Tests\Feature;

use App\Models\CentralKitchen;
use App\Models\Outlet;
use App\Service\CompositionService;
use App\Service\Impl\CompositionServiceImpl;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class TestCompositionService extends TestCase
{

    private CompositionService $compositionService;

    public function testFindOutletById()
    {
        $ck = CentralKitchen::create([
            'code' => fake()->name(),
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
        ]);

        $outlet = Outlet::create([
            'code' => fake()->name(),
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'central_kitchens_id' => $ck->id
        ]);

        $result = $this->compositionService->findOutletById($outlet->id);

        assertNotNull($result);
    }

    public function testFindCkById()
    {
        $ck = CentralKitchen::create([
            'code' => fake()->name(),
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
        ]);


        $result = $this->compositionService->findCentralKitchenById($ck->id);

        assertNotNull($result);
    }

    public function testFindOutletByIdNull()
    {

        $result = $this->compositionService->findOutletById('tidak ada');

        self::assertNull($result);
    }

    public function testGetPlacementOutlet()
    {

        $this->compositionService->getPlacement('9ac63a7b-a460-49a5-a490-6a34dc75d9bc', false);

    }


    protected function setUp(): void

    {
        parent::setUp();

        $this->compositionService = app()->make(CompositionServiceImpl::class);
    }

}
