<?php

namespace App\Repository\Warehuouse;

use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptDetail;
use App\Models\WarehouseItemReceiptRef;
use Illuminate\Support\Facades\Log;

class WarehouseItemReceiptRepository implements \App\Contract\Warehouse\WarehouseItemReceiptRepository
{

    public function findWarehouseItemReceiptRefById(string $id): WarehouseItemReceiptRef
    {
        return WarehouseItemReceiptRef::findOrFail($id);
    }

    public function findWarehouseItemReceiptById(string $id): WarehouseItemReceipt
    {
        return WarehouseItemReceipt::findOrFail($id);
    }

    public function setCodeExistingWarehouseItemReceipt(string $warehouseItemReceiptId, string $code, int $increment): bool
    {
        $warehouseItemReceipt = $this->findWarehouseItemReceiptById($warehouseItemReceiptId);
        $warehouseItemReceipt->code = $code;
        $warehouseItemReceipt->increment = $increment;
        return $warehouseItemReceipt->save();
    }

    /**
     * update jumlah quantity penerimaan dari warehouse item details yang sudah ada
     * @param array $itemDetails
     * @return bool
     * @throws \Exception
     */
    public function updateAmountReceivedExistingDetails(array $itemDetails): bool
    {
        foreach ($itemDetails as $item) {
            $detail = WarehouseItemReceiptDetail::findOrFail($item['id']);
            $detail->qty_accept = $item['qty_accept'];
            $result = $detail->save();

            // jika penyimpanan gagal maka lemparkan error
            if(!$result) {
                throw new \Exception(json_encode([
                    'message' => 'update updateAmountReceivedExistingDetails gagal',
                    'model' => $detail
                ]));
            }
        }
        return true;
    }
}
