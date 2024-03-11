<?php

namespace Tests\Feature\CentralKitchen;

use App\Contract\CentralKitchen\CentralKitchenRepository;
use App\Dto\CentralKitchenDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CentralKitchenRepositoryTest extends TestCase
{

    use RefreshDatabase;


    private CentralKitchenRepository $repository;

    protected function setUp():void
    {
        parent::setUp();
        $this->repository = new \App\Repository\CentralKitchen\CentralKitchenRepository();
    }


    public function testCreate()
    {

        $code = fake()->countryCode;

        $this->repository->create(new CentralKitchenDTO(
            $code,
            fake()->name,
        ));

        $this->assertDatabaseCount('central_kitchens', 1);
        $this->assertDatabaseHas('central_kitchens', ['code' => $code]);


    }


}
