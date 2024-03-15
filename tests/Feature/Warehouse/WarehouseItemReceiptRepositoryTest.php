<?php

namespace Tests\Feature\Warehouse;

use App\Contract\Warehouse\WarehouseItemReceiptRepository;
use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptDetail;
use App\Models\WarehouseItemReceiptRef;
use Database\Seeders\WarehouseItemReceiptSeederProduction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

class WarehouseItemReceiptRepositoryTest extends TestCase
{
    use RefreshDatabase;
    private WarehouseItemReceiptRepository $repository;

    protected function setUp():void
    {
        parent::setUp();
        $this->repository = new \App\Repository\Warehuouse\WarehouseItemReceiptRepository();

        // seeder dummy data
        $this->seed([WarehouseItemReceiptSeederProduction::class]);

    }

    public function testFindWarehouseItemReceiptRefByIdSuccess()
    {
        $itemReceipt = WarehouseItemReceiptRef::first();

        $result = $this->repository->findWarehouseItemReceiptRefById($itemReceipt->id );
        self::assertNotNull($result);
        self::assertInstanceOf(WarehouseItemReceiptRef::class, $result);
        self::assertEquals($itemReceipt->id, $result->id);
    }

    public function testFindWarehouseItemReceiptRefByIdFailed() {
        $this->expectException(\Exception::class);
        $result = $this->repository->findWarehouseItemReceiptRefById('asalasalan');
    }

    public function testFindWarehouseItemReceiptByIdSuccess() {
        $itemReceipt = WarehouseItemReceipt::first();
        $result = $this->repository->findWarehouseItemReceiptById($itemReceipt->id);
        assertNotNull($result);
        assertInstanceOf(WarehouseItemReceipt::class, $result);
        assertEquals($itemReceipt->id, $result->id);

    }


    public function testFindWarehouseItemReceiptByIdFailed() {
        $this->expectException(\Exception::class);
        $result = $this->repository->findWarehouseItemReceiptById('asalasalan');
    }

    public function testSetCodeExistingWarehouseItemReceiptSuccess() {
        $itemReceipt = WarehouseItemReceipt::first();
        $code = fake()->countryCode;
        $increment = 1;
        $result = $this->repository->setCodeExistingWarehouseItemReceipt($itemReceipt->id, $code, $increment );
        self::assertTrue($result);

        $result = $this->repository->findWarehouseItemReceiptById($itemReceipt->id);
        assertEquals($result->code, $code);
        assertEquals($result->increment, $increment);
    }

    public function testSetCodeExistingWarehouseItemReceiptFailedItemReceiptNotFound()
    {
        $this->expectException(\Exception::class);
        $code = fake()->countryCode;
        $increment = 1;
        $result = $this->repository->setCodeExistingWarehouseItemReceipt('idasalasalan', $code, $increment );
    }

    public function testSetCodeExistingWarehouseItemReceiptFailedCodeSame()
    {
        $itemReceipt = WarehouseItemReceipt::first();
        $code = fake()->countryCode;
        $increment = 1;

        $result = $this->repository->setCodeExistingWarehouseItemReceipt($itemReceipt->id, $code, $increment );
        assertTrue($result);

        $this->seed([WarehouseItemReceiptSeederProduction::class]);
        $itemReceipt2 = WarehouseItemReceipt::whereNotIn('id', [$itemReceipt->id])->get()->first();
        Log::info($itemReceipt2);
        self::assertCount(2, WarehouseItemReceipt::all());
        assertNotNull($itemReceipt2);
        assertNotEquals($itemReceipt->id, $itemReceipt2->id);

        $this->expectException(UniqueConstraintViolationException::class);
        $result = $this->repository->setCodeExistingWarehouseItemReceipt($itemReceipt2->id, $code, 2 );

    }

    public function testSetCodeExistingWarehouseItemReceiptFailedIncrementSame()
    {
        $itemReceipt = WarehouseItemReceipt::first();
        $code = fake()->countryCode;
        $increment = 1;

        $this->seed([WarehouseItemReceiptSeederProduction::class]);
        $itemReceipt2 = WarehouseItemReceipt::whereNotIn('id', [$itemReceipt->id])->get()->first();
        Log::info($itemReceipt2);
        self::assertCount(2, WarehouseItemReceipt::all());
        assertNotNull($itemReceipt2);
        assertNotEquals($itemReceipt->id, $itemReceipt2->id);

        $this->expectException(UniqueConstraintViolationException::class);
        $result = $this->repository->setCodeExistingWarehouseItemReceipt($itemReceipt2->id, fake()->code, $increment );
    }

    public function testUpdateAmountReceivedExistingDetailsSuccess()
    {
         $result = WarehouseItemReceiptDetail::all()->map(function($item) {
            return [
                'id' => $item->id,
                'qty_accept' => 10,
            ];
         })->toArray();
         self::assertIsArray($result);
         Log::info($result);

         $result = $this->repository->updateAmountReceivedExistingDetails($result);
         assertTrue($result);
    }

    public function testUpdateAmountReceivedExistingDetailsFailedWhenReceiptDetailNotFound()
    {
        $result = WarehouseItemReceiptDetail::all()->map(function($item) {
            return [
                'id' => fake()->uuid,
                'qty_accept' => 10,
            ];
        })->toArray();
        self::assertIsArray($result);

        $this->expectException(ModelNotFoundException::class);
        $result = $this->repository->updateAmountReceivedExistingDetails($result);
    }

    public function testUpdateAmountReceivedExistingDetailsFailedWhenQtyAcceptNotDecimal()
    {
        $result = WarehouseItemReceiptDetail::all()->map(function($item) {
            return [
                'id' => $item->id,
                'qty_accept' => fake()->name,
            ];
        })->toArray();
        self::assertIsArray($result);

        $this->expectException(QueryException::class);
        $result = $this->repository->updateAmountReceivedExistingDetails($result);
    }


}
