<?php

namespace App\Service\Warehouse;

use App\Contract\Warehouse\WarehouseItemReceiptRepository;
use App\Dto\WarehouseItemReceiptDTO;
use App\Models\CentralProduction;
use App\Models\Purchase;
use App\Models\WarehouseItemReceiptRef;
use Carbon\Carbon;
use Mockery\Exception;

class WarehouseItemReceiptService implements \App\Contract\Warehouse\WarehouseItemReceiptService
{
    protected WarehouseItemReceiptRepository $repository;

    /**
     * @param WarehouseItemReceiptRepository $repository
     */
    public function __construct(WarehouseItemReceiptRepository $repository)
    {
        $this->repository = $repository;
    }


    public function receipt(WarehouseItemReceiptDTO $itemReceiptDTO)
    {

        if (is_null($itemReceiptDTO) && !isset($itemReceiptDTO)) {
            report(new Exception('item receipt dto null'));
            abort(400);
        }

        $newDtoWithReceiptId = $this->setItemReceiptId($itemReceiptDTO);
        $model = $this->getWarehouseItemReceiptRef($newDtoWithReceiptId);
        $model = $model->receivable;

        // jalankan penerimaan item masuk gudang dari produksi
        if ($model instanceof CentralProduction) {
            $service = new WarehouseItemReceiptProductionService($this->repository);
            $service->receipt($itemReceiptDTO);
            return;
        }

        // jalankan penerimaan item masuk gudang dari pembelian
        if ($model instanceof Purchase) {
            $service = new WarehouseItemReceiptPurchaseService($this->repository);
            $service->receipt($itemReceiptDTO);
        }


    }

    protected function setItemReceiptId(WarehouseItemReceiptDTO $itemReceiptDTO): WarehouseItemReceiptDTO
    {
        // ambil item item receipt id terlebih dahulu
        $itemReceiptId = $this->getItemReceiptId($itemReceiptDTO->getWarehouseItemReceiptRefId());
        $itemReceiptDTO->setId($itemReceiptId);
        return $itemReceiptDTO;
    }

    private function getItemReceiptId(string $itemReceiptRefId)
    {
        return $this->repository->getWarehouseItemReceiptIdByRef($itemReceiptRefId);
    }

    private function getWarehouseItemReceiptRef(WarehouseItemReceiptDTO $itemReceiptDTO): WarehouseItemReceiptRef
    {
        $itemReceiptRefId = $itemReceiptDTO->getWarehouseItemReceiptRefId();

        // dapatkan model warehouse item receipt ref id dari repository warehouse item receipt repository
        return $this->repository->findWarehouseItemReceiptRefById($itemReceiptRefId);
    }

    protected function generateCode(string $itemReceiptId): array
    {
        // dapatkan kode dan id warehouse
        $warehouseCode = $this->repository->getWarehouseCodeByWarehouseReceipt($itemReceiptId);

        $warehouseId = $this->repository->getWarehouseIdByWarehouseReceipt($itemReceiptId);

        // format bulan dan tahun dan ambil next increment
        $currentMonthYear = $this->formatCurrentMonthYear();

        $nextCode = $this->getNextIncrement($warehouseId, $currentMonthYear);


        $code = "RECEIPT{$warehouseCode}{$currentMonthYear}{$nextCode}";


        return ['code' => $code, 'increment' => $nextCode];
    }

    protected function formatCurrentMonthYear(): string
    {
        return Carbon::now()->format('Ym');
    }

    protected function getNextIncrement(string $warehouseId, string $currentMonthYear): int
    {
        $latestCodeData = $this->repository->getLastCodeByWarehouse($warehouseId);

        return !empty($latestCodeData) && Carbon::parse($latestCodeData['created_at'])->format('Ym') === $currentMonthYear
            ? $latestCodeData['increment'] + 1
            : 1;
    }


}
