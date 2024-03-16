<?php

namespace Tests\Feature\Warehouse;

use App\Contract\Warehouse\WarehouseItemReceiptRepository;
use App\Dto\WarehouseItemReceiptDTO;
use App\Models\CentralProduction;
use App\Models\StockItem;
use App\Models\WarehouseItemReceiptRef;
use App\Service\Impl\CogsValuationCalc;
use App\Service\Warehouse\WarehouseItemReceiptService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\MockInterface;
use ReflectionClass;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsString;
use function PHPUnit\Framework\assertNotNull;

class WarehouseItemReceiptServiceTest extends TestCase
{

    private WarehouseItemReceiptService $service;


    public function testGetItemReceiptIdSuccess()
    {

        $refId = fake()->uuid;

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) use ($refId) {
            $mock->shouldReceive('getWarehouseItemReceiptIdByRef')->once()->andReturn($refId);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);
        $method = $reflection->getMethod('getItemReceiptId');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [fake()->uuid]);
        self::assertEquals($refId, $result);
        self::assertIsString($result);

    }

    public function testGetWarehouseItemReceiptRefSuccess()
    {
        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('findWarehouseItemReceiptRefById')->once()->andReturn(new WarehouseItemReceiptRef());
        });

        $refId = fake()->uuid;
        $dto = $this->mock(WarehouseItemReceiptDTO::class, function (MockInterface $mock) use ($refId) {
            $mock->shouldReceive('getWarehouseItemReceiptRefId')->once()->andReturn($refId);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);
        $method = $reflection->getMethod('getWarehouseItemReceiptRef');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [$dto]);
    }

    public function testGetWarehouseItemReceiptRefReceiptRefByIdNotFound()
    {
        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('findWarehouseItemReceiptRefById')->once()->andThrow(new ModelNotFoundException());
        });

        $refId = fake()->uuid;
        $dto = $this->mock(WarehouseItemReceiptDTO::class, function (MockInterface $mock) use ($refId) {
            $mock->shouldReceive('getWarehouseItemReceiptRefId')->once()->andReturn($refId);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $this->expectException(ModelNotFoundException::class);

        $method = $reflection->getMethod('getWarehouseItemReceiptRef');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [$dto]);
    }

    public function testFormatCurrentMonthYear()
    {
        $this->service = new WarehouseItemReceiptService(new \App\Repository\Warehuouse\WarehouseItemReceiptRepository());
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);
        $method = $reflection->getMethod('formatCurrentMonthYear');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, []);
        assertIsString($result);
        self::assertEquals(Carbon::now()->format('Ym'), $result);

    }

    public function testGetNextIncrementSameMonthSuccess()
    {

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getLastCodeByWarehouse')->once()->andReturn([
                'code' => 'WHIN20002',
                'increment' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);


        $method = $reflection->getMethod('getNextIncrement');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [fake()->uuid, Carbon::now()->format('Ym')]);
        assertNotNull($result);
        assertEquals(2, $result);

    }

    public function testGetNextIncrementSameMonthFailed()
    {

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getLastCodeByWarehouse')->once()->andThrow(new Exception('Warehouse item receipt null saat berusaha mendapatkan modelnya untuk keperluhan generate kode item masuk warehouse_id = $warehouseId'));
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);


        $method = $reflection->getMethod('getNextIncrement');
        $method->setAccessible(true);
        $this->expectException(Exception::class);
        $result = $method->invokeArgs($this->service, [fake()->uuid, Carbon::now()->format('Ym')]);
        assertEquals(2, $result);

    }


    public function testGetNextIncrementNextMonthSuccess()
    {

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getLastCodeByWarehouse')->once()->andReturn([
                'code' => 'WHIN20002',
                'increment' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);


        $method = $reflection->getMethod('getNextIncrement');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [fake()->uuid, Carbon::now()->addMonth(1)->format('Ym')]);
        assertNotNull($result);
        assertEquals(1, $result);

    }

    public function testGenerateCodeSuccess()
    {

        $warehouseCode = fake()->countryCode;
        $warehouseId = fake()->uuid;

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) use ($warehouseCode, $warehouseId) {
            $mock->shouldReceive('getWarehouseCodeByWarehouseReceipt')->once()->andReturn($warehouseCode);
            $mock->shouldReceive('getWarehouseIdByWarehouseReceipt')->once()->andReturn($warehouseId);
            $mock->shouldReceive('getLastCodeByWarehouse')->once()->andReturn([
                'code' => 'WHIN20002',
                'increment' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);


        $method = $reflection->getMethod('generateCode');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [fake()->uuid]);
        assertNotNull($result);

        $monthYear = Carbon::now()->format('Ym');
        assertEquals(2, $result['increment']);
        assertEquals("RECEIPT{$warehouseCode}{$monthYear}2", $result['code']);
    }

    public function testGenerateCodeNextMonthSuccess()
    {

        $warehouseCode = fake()->countryCode;
        $warehouseId = fake()->uuid;

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) use ($warehouseCode, $warehouseId) {
            $mock->shouldReceive('getWarehouseCodeByWarehouseReceipt')->once()->andReturn($warehouseCode);
            $mock->shouldReceive('getWarehouseIdByWarehouseReceipt')->once()->andReturn($warehouseId);
            $mock->shouldReceive('getLastCodeByWarehouse')->once()->andReturn([
                'code' => 'WHIN20002',
                'increment' => 1,
                'created_at' => Carbon::now()->ceilMonth(1)->format('Y-m-d H:i:s')
            ]);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);


        $method = $reflection->getMethod('generateCode');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [fake()->uuid]);
        assertNotNull($result);

        $monthYear = Carbon::now()->format('Ym');
        assertEquals(1, $result['increment']);
        assertEquals("RECEIPT{$warehouseCode}{$monthYear}1", $result['code']);
    }


    public function testProcessProductionAcceptanceSuccess()
    {

        $warehouseCode = fake()->countryCode;
        $warehouseId = fake()->uuid;

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) use ($warehouseCode, $warehouseId) {
            $mock->shouldReceive('getWarehouseCodeByWarehouseReceipt')->once()->andReturn($warehouseCode);
            $mock->shouldReceive('getWarehouseIdByWarehouseReceipt')->twice()->andReturn($warehouseId);
            $mock->shouldReceive('getLastCodeByWarehouse')->once()->andReturn([
                'code' => 'WHIN20002',
                'increment' => 1,
                'created_at' => Carbon::now()->ceilMonth(1)->format('Y-m-d H:i:s')
            ]);
            $mock->shouldReceive('setCodeExistingWarehouseItemReceipt')->once()->andReturn(true);
            $mock->shouldReceive('updateAmountReceivedExistingDetails')->once()->andReturn(true);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);


        $method = $reflection->getMethod('processProductionAcceptance');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [new CentralProduction(), fake()->uuid, []]);
    }

    public function testProcessProductionAcceptanceFailedSetCodeExistingWarehouseItemReceiptException()
    {

        $warehouseCode = fake()->countryCode;
        $warehouseId = fake()->uuid;

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) use ($warehouseCode, $warehouseId) {
            $mock->shouldReceive('getWarehouseCodeByWarehouseReceipt')->once()->andReturn($warehouseCode);
            $mock->shouldReceive('getWarehouseIdByWarehouseReceipt')->once()->andReturn($warehouseId);
            $mock->shouldReceive('getLastCodeByWarehouse')->once()->andReturn([
                'code' => 'WHIN20002',
                'increment' => 1,
                'created_at' => Carbon::now()->ceilMonth(1)->format('Y-m-d H:i:s')
            ]);
            $mock->shouldReceive('setCodeExistingWarehouseItemReceipt')->once()->andThrow(new Exception('query gagal mengatur code'));
            $mock->shouldReceive('updateAmountReceivedExistingDetails')->never()->andReturn(true);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $this->expectException(Exception::class);

        $method = $reflection->getMethod('processProductionAcceptance');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [new CentralProduction(), fake()->uuid, []]);
    }

    public function testProcessProductionAcceptanceFailedSetCodeExistingWarehouseItemReceipt()
    {

        $warehouseCode = fake()->countryCode;
        $warehouseId = fake()->uuid;

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) use ($warehouseCode, $warehouseId) {
            $mock->shouldReceive('getWarehouseCodeByWarehouseReceipt')->once()->andReturn($warehouseCode);
            $mock->shouldReceive('getWarehouseIdByWarehouseReceipt')->once()->andReturn($warehouseId);
            $mock->shouldReceive('getLastCodeByWarehouse')->once()->andReturn([
                'code' => 'WHIN20002',
                'increment' => 1,
                'created_at' => Carbon::now()->ceilMonth(1)->format('Y-m-d H:i:s')
            ]);
            $mock->shouldReceive('setCodeExistingWarehouseItemReceipt')->once()->andReturn(false);
            $mock->shouldReceive('updateAmountReceivedExistingDetails')->never()->andReturn(true);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $this->expectException(Exception::class);

        $method = $reflection->getMethod('processProductionAcceptance');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [new CentralProduction(), fake()->uuid, []]);
    }

    public function testProcessProductionAcceptanceFailedUpdateAmountReceivedExistingDetailsException()
    {

        $warehouseCode = fake()->countryCode;
        $warehouseId = fake()->uuid;

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) use ($warehouseCode, $warehouseId) {
            $mock->shouldReceive('getWarehouseCodeByWarehouseReceipt')->once()->andReturn($warehouseCode);
            $mock->shouldReceive('getWarehouseIdByWarehouseReceipt')->once()->andReturn($warehouseId);
            $mock->shouldReceive('getLastCodeByWarehouse')->once()->andReturn([
                'code' => 'WHIN20002',
                'increment' => 1,
                'created_at' => Carbon::now()->ceilMonth(1)->format('Y-m-d H:i:s')
            ]);
            $mock->shouldReceive('setCodeExistingWarehouseItemReceipt')->once()->andReturn(true);
            $mock->shouldReceive('updateAmountReceivedExistingDetails')->once()->andThrow(new Exception('gagal update amount received'));
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $this->expectException(Exception::class);

        $method = $reflection->getMethod('processProductionAcceptance');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [new CentralProduction(), fake()->uuid, []]);
    }

    public function testProcessProductionAcceptanceFailedUpdateAmountReceivedExistingDetailsFalse()
    {

        $warehouseCode = fake()->countryCode;
        $warehouseId = fake()->uuid;

        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) use ($warehouseCode, $warehouseId) {
            $mock->shouldReceive('getWarehouseCodeByWarehouseReceipt')->once()->andReturn($warehouseCode);
            $mock->shouldReceive('getWarehouseIdByWarehouseReceipt')->once()->andReturn($warehouseId);
            $mock->shouldReceive('getLastCodeByWarehouse')->once()->andReturn([
                'code' => 'WHIN20002',
                'increment' => 1,
                'created_at' => Carbon::now()->ceilMonth(1)->format('Y-m-d H:i:s')
            ]);
            $mock->shouldReceive('setCodeExistingWarehouseItemReceipt')->once()->andReturn(true);
            $mock->shouldReceive('updateAmountReceivedExistingDetails')->once()->andReturn(false);
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $this->expectException(Exception::class);

        $method = $reflection->getMethod('processProductionAcceptance');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [new CentralProduction(), fake()->uuid, []]);
    }

    public function testInsertRemainingStockSuccess()
    {
        $repository = $this->mock(WarehouseItemReceiptRepository::class, function (MockInterface $mock) {
            $stockItem = new StockItem();
            $stockItem->incoming_qty = 10;
            $stockItem->incoming_value = 0;
            $stockItem->price_diff= 0;
            $stockItem->inventory_value = 10000;
            $stockItem->qty_on_hand = 10;
            $stockItem->avg_cost = 1000;
            $stockItem->last_cost = 1000;
            $stockItem->minimum_stock = 0;

            $mock->shouldReceive('getStockItemByWarehouseItemId')
                ->once()
                ->andReturn($stockItem);

            $mock->shouldReceive('insertNewStockItem')->once();
        });

        $this->service = new WarehouseItemReceiptService($repository);
        $reflection = new ReflectionClass(get_class($this->service));
        self::assertNotNull($reflection);

        $method = $reflection->getMethod('insertRemainingStock');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->service, [new CogsValuationCalc(), '', ['qty' => 10, 'avg_cost' => 10]]);
        self::assertInstanceOf(StockItem::class, $result);
    }


}
