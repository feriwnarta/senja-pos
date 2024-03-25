<?php

namespace App\Service\Warehouse;

use App\Contract\Warehouse\WarehouseItemReceiptRepository;
use App\Dto\WarehouseItemReceiptDTO;
use App\Models\CentralProduction;
use App\Models\Purchase;
use App\Models\WarehouseItemReceiptRef;
use Mockery\Exception;

class WarehouseItemReceiptService implements \App\Contract\Warehouse\WarehouseItemReceiptService
{
    private WarehouseItemReceiptRepository $repository;

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

    private function setItemReceiptId(WarehouseItemReceiptDTO $itemReceiptDTO): WarehouseItemReceiptDTO
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

}
