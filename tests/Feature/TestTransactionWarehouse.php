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


        $result = $this->warehouseTransactionService->generateCodeRequest(false, '9ad1e5b4-cdd9-4921-bfbd-20c71e4be5f1');
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


    protected function setUp(): void
    {
        parent::setUp();
        $this->warehouseTransactionService = app()->make(WarehouseTransactionServiceImpl::class);
    }


}
