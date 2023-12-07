<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CentralKitchen;
use App\Models\Outlet;
use App\Models\Unit;
use App\Service\CompositionService;
use App\Service\Impl\CompositionServiceImpl;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function testSaveItemRouteBuy()
    {

        $route = 'BUY';
        $routeProduce = 'PRODUCECENTRALKITCHEN';
        $inStock = '0';
        $minimumStock = '10';
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf');
        $isOutlet = false;

        $unit = Unit::create(['code' => fake()->currencyCode(), 'name' => fake()->name()]);

        $category = Category::create(['code' => fake()->currencyCode(), 'name' => fake()->name()]);
        $central = CentralKitchen::create(['code' => fake()->currencyCode(), 'name' => fake()->name(), 'address' => fake()->address(), 'phone' => fake()->phoneNumber(), 'email' => fake()->email()]);

        $result = $this->compositionService->saveItem($route, $routeProduce, $inStock, $minimumStock, $file, $isOutlet, null, fake()->currencyCode(), fake()->name(), $unit->id, '', $category->id, $central->id);

        self::assertEquals('success', $result);

        $this->expectsDatabaseQueryCount(1);


        // Assertion: Pastikan entitas Item telah disimpan
        $this->assertDatabaseHas('items', [
            'route' => $route,
            // Sesuaikan field lainnya sesuai kebutuhan Anda
        ]);


    }


    public function testSaveItemRouteProduce()
    {

        $route = 'PRODUCE';
        $routeProduce = 'PRODUCECENTRALKITCHEN';
        $inStock = '0';
        $minimumStock = '10';
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf');
        $isOutlet = false;

        $unit = Unit::create(['code' => fake()->currencyCode(), 'name' => fake()->name()]);

        $category = Category::create(['code' => fake()->currencyCode(), 'name' => fake()->name()]);
        $central = CentralKitchen::create(['code' => fake()->currencyCode(), 'name' => fake()->name(), 'address' => fake()->address(), 'phone' => fake()->phoneNumber(), 'email' => fake()->email()]);

        $result = $this->compositionService->saveItem($route, $routeProduce, $inStock, $minimumStock, $file, $isOutlet, null, fake()->currencyCode(), fake()->name(), $unit->id, '', $category->id, $central->id);

        self::assertEquals('success', $result);

        $this->expectsDatabaseQueryCount(1);


        // Assertion: Pastikan entitas Item telah disimpan
        $this->assertDatabaseHas('items', [
            'route' => $routeProduce,
            // Sesuaikan field lainnya sesuai kebutuhan Anda
        ]);

    }


    public function testSaveItemRouteProduceOutlet()
    {

        $route = 'PRODUCE';
        $routeProduce = 'PRODUCEOUTLET';
        $inStock = '0';
        $minimumStock = '10';
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf');
        $isOutlet = true;

        $unit = Unit::create(['code' => fake()->currencyCode(), 'name' => fake()->name()]);

        $category = Category::create(['code' => fake()->currencyCode(), 'name' => fake()->name()]);
        $central = CentralKitchen::create(['code' => fake()->currencyCode(), 'name' => fake()->name(), 'address' => fake()->address(), 'phone' => fake()->phoneNumber(), 'email' => fake()->email()]);
        $outlet = Outlet::create(['central_kitchens_id' => $central->id, 'code' => fake()->currencyCode(), 'name' => fake()->name(), 'address' => fake()->address(), 'phone' => fake()->phoneNumber(), 'email' => fake()->email()]);

        assertNotNull($outlet);


        $result = $this->compositionService->saveItem($route, $routeProduce, $inStock, $minimumStock, $file, $isOutlet, null, fake()->currencyCode(), fake()->name(), $unit->id, '', $category->id, $outlet->id);

        self::assertEquals('success', $result);

        $this->expectsDatabaseQueryCount(1);


        // Assertion: Pastikan entitas Item telah disimpan
        $this->assertDatabaseHas('items', [
            'route' => $routeProduce,
            // Sesuaikan field lainnya sesuai kebutuhan Anda
        ]);

    }

    public function testSaveItemRouteNotFound()
    {

        $route = 'TEST';
        $routeProduce = 'PRODUCEOUTLET';
        $inStock = '0';
        $minimumStock = '10';
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf');
        $isOutlet = true;

        $unit = Unit::create(['code' => fake()->currencyCode(), 'name' => fake()->name()]);

        $category = Category::create(['code' => fake()->currencyCode(), 'name' => fake()->name()]);
        $central = CentralKitchen::create(['code' => fake()->currencyCode(), 'name' => fake()->name(), 'address' => fake()->address(), 'phone' => fake()->phoneNumber(), 'email' => fake()->email()]);
        $outlet = Outlet::create(['central_kitchens_id' => $central->id, 'code' => fake()->currencyCode(), 'name' => fake()->name(), 'address' => fake()->address(), 'phone' => fake()->phoneNumber(), 'email' => fake()->email()]);

        assertNotNull($outlet);


        $result = $this->compositionService->saveItem($route, $routeProduce, $inStock, $minimumStock, $file, $isOutlet, null, fake()->currencyCode(), fake()->name(), $unit->id, '', $category->id, $outlet->id);

        self::assertEquals('Rute diluar yang ditentukan', $result);

        $this->expectsDatabaseQueryCount(1);


        // Assertion: Pastikan entitas Item telah disimpan
        $this->assertDatabaseHas('items', [
            'route' => $routeProduce,
            // Sesuaikan field lainnya sesuai kebutuhan Anda
        ]);

    }


    protected function setUp(): void

    {
        parent::setUp();

        $this->compositionService = app()->make(CompositionServiceImpl::class);
    }

}
