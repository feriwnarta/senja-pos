<?php

namespace Tests\Feature;

use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Service\Impl\PurchaseServiceImpl;
use App\Service\PurchaseService;
use Tests\TestCase;

class TestPurchaseService extends TestCase
{

    private PurchaseService $purchaseService;

    public function testGenerateCodePurchase()
    {
        $code = $this->purchaseService->generateCodePurchase('TEST');
        self::assertIsArray($code);
        print_r($code);

    }

    public function testCreatePurchaseFromRqStock()
    {

        $data = [
            'purchaseRequestId' => '9b34a0fe-ebae-4c6c-8127-1e6f21033381',
            'itemId' => '9b28cd5c-ea10-424c-ac32-5e44b73433b4',
            'itemName' => 'Garam',
            'supplier' => '9b2c565b-db75-48ef-8594-8b884071adad',
            'payment' => 'NET',
            'stockActual' => 0.00,
            'qtyBuy' => 100.00,
            'unitName' => 'KG',
            'unitPrice' => '5,000',
            'purchaseAmount' => 100,
            'totalAmount' => 500000,
            'deadlinePayment' => 3,
        ];


        $purchaseReqId = PurchaseRequest::latest()->first();
        $supplier = Supplier::latest()->first();
        $result = $this->purchaseService->createPurchaseNetFromRequestStock($purchaseReqId->id, $supplier->id, 'NET', '1', [$data]);
        self::assertTrue($result);

    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->purchaseService = app()->make(PurchaseServiceImpl::class);
    }


}
