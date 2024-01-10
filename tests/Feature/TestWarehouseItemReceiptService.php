<?php

namespace Tests\Feature;

use App\Models\WarehouseItemReceipt;
use App\Service\Impl\WarehouseItemReceiptServiceImpl;
use App\Service\WarehouseItemReceiptService;
use Carbon\Carbon;
use Tests\TestCase;

class TestWarehouseItemReceiptService extends TestCase
{
    public WarehouseItemReceiptService $itemReceiptService;

    public function testGenerateCode()
    {
        $itemReceiptId = WarehouseItemReceipt::first()->id;
        $warehouseId = WarehouseItemReceipt::first()->warehouses_id;
        $warehouseCode = WarehouseItemReceipt::first()->warehouse->warehouse_code;

        $code = $this->itemReceiptService->generateCodeReceipt($itemReceiptId, $warehouseId, $warehouseCode);
        print_r($code);
        self::assertIsArray($code);

    }

    public function testAcceptItemReceipt()
    {
        $itemReceiptId = WarehouseItemReceipt::first()->id;
        $warehouseId = WarehouseItemReceipt::first()->warehouses_id;
        $warehouseCode = WarehouseItemReceipt::first()->warehouse->warehouse_code;
        $detailId = WarehouseItemReceipt::first()->first()->details()->latest()->first()->id;
        $detailAccept = WarehouseItemReceipt::first()->first()->details()->latest()->first()->qty_accept;

        $result = $this->itemReceiptService->accept($itemReceiptId, $warehouseId, $warehouseCode, [
            [
                'id' => '9b0e4ba7-8521-414f-ac1a-b4731a2059fb',
                'qty_accept' => '5.00'
            ]
        ]);

        self::assertTrue($result);
    }

    public function testAcceptItemReceiptNextCode()
    {

        $itemReceiptId = WarehouseItemReceipt::latest()->first()->id;
        $warehouseId = WarehouseItemReceipt::latest()->first()->warehouses_id;
        $warehouseCode = WarehouseItemReceipt::latest()->first()->warehouse->warehouse_code;
        $detailId = WarehouseItemReceipt::latest()->first()->details()->latest()->first()->id;
        $detailAccept = WarehouseItemReceipt::latest()->first()->details()->latest()->first()->qty_accept;


        $result = $this->itemReceiptService->accept($itemReceiptId, $warehouseId, $warehouseCode, [
            [
                'id' => $detailId,
                'qty_accept' => $detailAccept,
            ]
        ]);

        self::assertTrue($result);

    }

    public function testGenerateCodeNextMonth()
    {
        $itemReceiptId = WarehouseItemReceipt::latest()->first()->id;
        $warehouseId = WarehouseItemReceipt::latest()->first()->warehouses_id;
        $warehouseCode = WarehouseItemReceipt::latest()->first()->warehouse->warehouse_code;
        $detailId = WarehouseItemReceipt::latest()->first()->details()->latest()->first()->id;
        $detailAccept = WarehouseItemReceipt::latest()->first()->details()->latest()->first()->qty_accept;

        Carbon::setTestNow(Carbon::create(2024, 2, 1));

        $result = $this->itemReceiptService->accept($itemReceiptId, $warehouseId, $warehouseCode, [
            [
                'id' => $detailId,
                'qty_accept' => $detailAccept,
            ]
        ]);

        self::assertTrue($result);
    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->itemReceiptService = app()->make(WarehouseItemReceiptServiceImpl::class);
    }


}
