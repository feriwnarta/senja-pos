<?php

namespace App\Service\Warehouse;

use App\Dto\WarehouseItemReceiptDTO;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseItemReceiptPurchaseService extends WarehouseItemReceiptService implements \App\Contract\Warehouse\WarehouseItemReceiptService
{

    public function receipt(WarehouseItemReceiptDTO $itemReceiptDTO)
    {
        try {

            DB::transaction(function () use ($itemReceiptDTO) {

                $newDtoWithReceiptId = $this->setItemReceiptId($itemReceiptDTO);
                $itemReceiptId = $newDtoWithReceiptId->getId();

                // generate code item masuk
                Log::info('generate kode item receipt');
                $result = $this->generateCode($itemReceiptId);


                if (empty($result)) {
                    Log::error(json_encode([
                        'message' => "hasil generate kode array kosong saat penerimaan barang produksi",
                        'data' => $itemReceiptDTO
                    ]));
                    report(new Exception('hasil generate kode array kosong saat penerimaan barang produksi'));
                    return;
                }

                // set code ke item receipt yang sudah dibuat
                $code = $result['code'];
                $increment = $result['increment'];


                Log::info('set kode item receipt');
                // set kode dan incerement ke warehouse item receipt yang sudah dibuat
                $isSetCode = $this->repository->setCodeExistingWarehouseItemReceipt($itemReceiptId, $code, $increment);


                // jika is set code false, menandakan bahwa query update kode ke item receipt yang sudah ada gagal
                if (!$isSetCode) {
                    Log::error(json_encode([
                        'message' => 'gagal menyimpan kode ke item receipt yang sudah dibuat',
                        "data" => [
                            $newDtoWithReceiptId,
                            $result
                        ],
                    ]));
                    report(new Exception('gagal menyimpan kode ke item receipt yang sudah dibuat'));
                    return;
                }

                // update nilai jumlah diterima
                Log::info('set jumlah dan item yang diterima ke penerimaan detail');
                // buat detail penerimaan item beserta nilai quantity yang diterimanya
                $isUpdateAmountReceived = $this->repository->updateAmountReceivedExistingDetails($newDtoWithReceiptId->getDataItemReceipt());
                if (!$isUpdateAmountReceived) {
                    Log::error(json_encode([
                        "message" => "gagal update Amount Received Existing Details dipenerimaan barang produksi",
                        "data" => [
                            $newDtoWithReceiptId,
                        ]
                    ]));
                    report(new Exception('gagal update Amount Received Existing Details dipenerimaan barang produksi'));
                    return;
                }

                // insert ke stock inventory valuation
                $warehouseId = $this->repository->getWarehouseIdByWarehouseReceipt($itemReceiptId);

                // buat history item receipt

                // buat pembayaran jika tipe pembelian adalah NET

                dd($isSetCode);
            });

        } catch (Exception $exception) {
            Log::error(json_encode([
                "message" => "gagal melakukan penerimaan item masuk dari pembelian",
                "data" => $itemReceiptDTO
            ]));
            report($exception);
            abort(400);
        }


    }


}
