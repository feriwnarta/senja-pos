<?php

namespace App\Dto;

use App\Models\WarehouseItemReceiptRef;

class WarehouseItemReceiptDTO
{

    private string $id;
    private string $code;
    private string $warehouseItemReceiptRefId;
    private string $warehouseId;
    private array $dataItemReceipt;

    /**
     * @param string $warehouseItemReceiptRefId
     * @param string $warehouseId
     * @param array $dataItemReceipt
     */
    public function __construct(string $warehouseItemReceiptRefId, string $warehouseId, array $dataItemReceipt)
    {
        $this->warehouseItemReceiptRefId = $warehouseItemReceiptRefId;
        $this->warehouseId = $warehouseId;
        $this->dataItemReceipt = $dataItemReceipt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getWarehouseItemReceiptRefId(): string
    {
        return $this->warehouseItemReceiptRefId;
    }

    public function setWarehouseItemReceiptRefId(string $warehouseItemReceiptRefId): void
    {
        $this->warehouseItemReceiptRefId = $warehouseItemReceiptRefId;
    }

    public function getWarehouseId(): string
    {
        return $this->warehouseId;
    }

    public function setWarehouseId(string $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }

    public function getDataItemReceipt(): array
    {
        return $this->dataItemReceipt;
    }

    public function setDataItemReceipt(array $dataItemReceipt): void
    {
        $this->dataItemReceipt = $dataItemReceipt;
    }




}
