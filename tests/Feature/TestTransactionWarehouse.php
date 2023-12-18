<?php

namespace Tests\Feature;

use App\Service\Impl\WarehouseTransactionServiceImpl;
use App\Service\WarehouseTransactionService;
use Tests\TestCase;
use function PHPUnit\Framework\assertIsString;

class TestTransactionWarehouse extends TestCase
{
    public WarehouseTransactionService $warehouseTransactionService;

    public function testGenerateCode()
    {


        $result = $this->warehouseTransactionService->generateCodeRequest(false, '9ad7d9d2-3bcc-4e17-a38a-86f7da2d6527');
        print_r($result);
        self::assertNotNull($result);
        self::assertIsArray($result);

    }

    public function testGenerateCodeFailedCkNotFound()
    {
        $result = $this->warehouseTransactionService->generateCodeRequest(false, '9ad1e58f-590a-44fd-80c7-2', '9ad1e5b4-cdd9-4921-bfbd-20c71e4be5f1');
        self::assertNull($result);
    }


    public function testGenerateCodeFailedWarehouseNotFound()
    {
        $result = $this->warehouseTransactionService->generateCodeRequest(false, '9ad1e58f-590a-44fd-80c7-dab6cbaeef9c', '9ad1e5b4-cdd9-49s21-bfbd-20c71e4be5f1');
        self::assertNull($result);
    }

    public function testCreateRequestCentralKitchen()
    {
        $result = $this->warehouseTransactionService->createRequest(false, '9ad1e5b4-cdd9-4921-bfbd-20c71e4be5f1');
        self::assertNotNull($result);
        assertIsString($result->id);
    }

    public function testFinishCreateRequestCk()
    {
        $result = $this->warehouseTransactionService->finishRequest('9ad5d471-41a6-4c9a-addf-9508ed0da3a8', [
            [
                'id' => '9ad1ee71-1db7-46aa-a1f8-9901f1ac6a96',
                'itemReq' => '10',
            ],
            [
                'id' => '9ad4198b-e125-4f42-9b06-7b35ee44f40f',
                'itemReq' => '20',
            ]
        ]);

        assertIsString($result);

    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->warehouseTransactionService = app()->make(WarehouseTransactionServiceImpl::class);
    }


}
