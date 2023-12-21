<?php

namespace Tests\Feature;

use App\Models\CentralKitchen;
use App\Models\CentralProduction;
use App\Models\RequestStock;
use App\Service\CentralProductionService;
use App\Service\Impl\CentralProductionServiceImpl;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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


    protected function setUp(): void
    {
        parent::setUp();

        $this->centralService = app()->make(CentralProductionServiceImpl::class);
//        DB::table('central_productions')->delete();
    }

}
