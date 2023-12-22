<?php

namespace Tests\Feature;

use App\Models\CentralKitchen;
use App\Models\CentralProduction;
use App\Models\RequestStock;
use App\Service\CentralProductionService;
use App\Service\Impl\CentralProductionServiceImpl;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class TestCentralProductionService extends TestCase
{

    private CentralProductionService $centralService;

    public function testGenerateCode()
    {

        $req = RequestStock::first();
        $central = CentralKitchen::first();

        $result = $this->centralService->generateCode($req->id, $central->id);
        print_r($result);
        self::assertNotNull($result);
        self::assertIsArray($result);

        self::assertEquals('PRDCENTRALPORIS01202312211', $result['code']);
    }

    public function testGenerateCodeReqStockNotFound()
    {

        $central = CentralKitchen::first();
        $this->expectException(ModelNotFoundException::class);
        $result = $this->centralService->generateCode('asd', $central->id);
    }

    public function testGenerateCodeReqNextMonth()
    {

        $req = RequestStock::first();
        $central = CentralKitchen::first();

        $result = CentralProduction::create([
            'request_stocks_id' => $req->id,
            'code' => 'test',
            'increment' => '1',
            'central_kitchens_id' => $central->id

        ]);

        $this->expectsDatabaseQueryCount(5);
        // Atur waktu saat ini ke 1 Januari 2024 untuk mensimulasikan pergantian bulan
        Carbon::setTestNow(Carbon::create(2024, 1, 1));
        $code = $this->centralService->generateCode($req->id, $central->id);
        print_r($code);

        assertNotNull($code);
    }

    public function testGenerateCodeIfExistData()
    {

        $req = RequestStock::first();

        $central = CentralKitchen::first();
        $result = CentralProduction::create([
            'request_stocks_id' => $req->id,
            'code' => 'test',
            'increment' => '1',
            'central_kitchens_id' => $central->id

        ]);

        $this->expectsDatabaseQueryCount(5);
        // Atur waktu saat ini ke 1 Januari 2024 untuk mensimulasikan pergantian bulan
        $code = $this->centralService->generateCode($req->id);
        print_r($code);

        assertNotNull($code);
        self::assertEquals('PRDCENTRALPORIS01202312212', $code['code']);
    }

    public function testSaveProduction()
    {

        $req = RequestStock::first();
        $central = CentralKitchen::first();
        $result = $this->centralService->createProduction($req->id, $central->id);

        assertNotNull($result);
        self::assertIsString($result);
    }

    public function testGetReqStock()
    {

        $result = RequestStock::findOrFail('9ae48c86-9620-45c4-9f74-b90ecae71f84')->warehouse->centralKitchen->first()->id;

        Log::info($result);

        print_r($result);
        assertNotNull($result);

    }

    public function testSaveComponentRequest()
    {

        $component = [
            [
                'item' => [
                    'id' => '9ae48c16-be27-4dca-a209-85f6339f82fb',
                    'name' => 'Ayam giling',
                ],
                'recipe' => [
                    [
                        'isChecked' => true,
                        'id' => '9ae48c6a-0801-4c31-8b11-e446e9d27b98',
                        'item_component_id' => '9ad7dbdd-b4b2-46e6-85e6-2c9ff19551b3',
                        'item_component_name' => 'Paha ayam',
                        'item_component_unit' => 'GR',
                        'item_component_usage' => '150.00',
                        'qty_request' => 0,
                    ],
                ],
            ],
            [
                'item' => [
                    'id' => '9ad7dca8-15c8-4f58-8ab8-c696191328dd',
                    'name' => 'Bawang goreng',
                ],
                'recipe' => [
                    [
                        'isChecked' => true,
                        'id' => '9ad7dd55-7e48-4d31-85f2-4f3fb0e997db',
                        'item_component_id' => '9ad7dd21-4cc8-4554-97a8-8fc06ebdc689',
                        'item_component_name' => 'Garam',
                        'item_component_unit' => 'KG',
                        'item_component_usage' => '10.00',
                        'qty_request' => 0,
                    ],
                ],
            ],
        ];

        $prodId = CentralProduction::first()->id;

        $rs = $this->centralService->saveComponent($prodId, $component);
        assertNotNull($rs);
        self::assertTrue($rs);
        print_r($rs);


    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->centralService = app()->make(CentralProductionServiceImpl::class);
        DB::table('central_production_results')->delete();
//        DB::table('central_productions')->delete();
    }

}
