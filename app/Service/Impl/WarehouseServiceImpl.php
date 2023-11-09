<?php

namespace App\Service\Impl;

use App\Models\Warehouse;
use App\Service\WarehouseService;

class WarehouseServiceImpl implements WarehouseService
{

    public function getDetailWarehouse(string $id): Warehouse
    {

        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            $callStack = debug_backtrace();

            // Ambil fungsi yang memanggil method ini
            $callingFunction = $callStack[1]['function'];
            throw new \Exception("detail warehouse tidak ditemukan karena data null $callingFunction", code: 1);
        }

        return $warehouse;
    }

    public function getDetailDataAreaRackItemWarehouse(?Warehouse $warehouse): array
    {
        $areas = [];

        if (null) {
            $callStack = debug_backtrace();

            // Ambil fungsi yang memanggil method ini
            $callingFunction = $callStack[1]['function'];

            throw new \Exception("data warehouse dari $callingFunction nullable", code: 2);
        }


        foreach ($warehouse->areas as $area) {
            // tambah rak
            $arrRack = [];

            foreach ($area->racks as $rack) {
                $arrItem = [];

                // Ambil maksimal 5 item dari rak
                $itemCount = 0;
                foreach ($rack->item as $item) {
                    $arrItem[] = [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];

                    $itemCount++;

                    // Hentikan loop jika sudah mencapai 5 item
                    if ($itemCount == 5) {
                        break;
                    }
                }

                $arrRack[] = [
                    'id' => $rack->id,
                    'name' => $rack->name,
                    'category_inventory' => $rack->category_inventory,
                    'item' => $arrItem,
                ];
            }

            // data area
            $areas[] = [
                'area' => [
                    'id' => $area->id,
                    'area' => $area->name,
                    'racks' => $arrRack,
                ]
            ];
        }


        return $areas;
    }
}
