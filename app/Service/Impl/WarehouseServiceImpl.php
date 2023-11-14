<?php

namespace App\Service\Impl;

use App\Models\Area;
use App\Models\Item;
use App\Models\Rack;
use App\Models\Warehouse;
use App\Service\WarehouseService;
use Illuminate\Support\Facades\Log;

class WarehouseServiceImpl implements WarehouseService
{

    /** dapatkan data warehouse berdasarkan id
     * @param string $id
     * @return Warehouse
     * @throws \Exception
     */
    public function getWarehouseById(string $id): Warehouse
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

    /**
     * fungsi dari service ini bergunakan untuk mendapatkan detail data area, rack dan item dari gudang yang dipilih berdasarkan model warehouse
     * saat model warehouse null maka akan mengembalikan exception null dengan kode 2
     * perhatikan bahwa jumlah item yang diambil untuk masing masing area dan rak sebanyak 5 saja
     * ini digunakan untuk keperluan performa
     * @param Warehouse|null $warehouse
     * @return array
     * @throws \Exception
     */
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

    public function nextCursorItemRack(string $rackId, string $nextCursorId): array
    {
        try {
            return Item::where('racks_id', $rackId)
                ->orderBy('id')
                ->cursorPaginate(10, ['*'], 'cursor', $nextCursorId)
                ->toArray();
        } catch (\Exception $exception) {
            return [];
            Log::error($exception->getMessage());
        }
    }

    public function getItemRackAddedByIdWithCursor(string $id): array
    {
        return $this->getItemRackByIdWithCursor($id);
    }

    /**
     * dapatkan detail item  berdasarkan id rack dengan menggunakan konsep cursor
     * balikan fungsi ini berupa array cursor, hal ini karena livewire tidak bisa menangani
     * data cursor
     * @param string $id
     * @return array
     */
    public function getItemRackByIdWithCursor(string $id): array
    {
        try {
            return Item::where('racks_id', $id)->orWhereNull('racks_id')->orderBy('id')->cursorPaginate(10)->toArray();
        } catch (\Exception $exception) {
            return [];
            Log::error($exception->getMessage());
        }

    }

    public function manipulateItemRackAdded(array $dataItem, string $id): array
    {

        Log::error($dataItem);

        $returnData = [];
        foreach ($dataItem as $item) {
            if ($item['racks_id'] == $id) {
                $returnData[] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'checked' => 'true',
                ];
                continue;
            }

            $returnData[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'checked' => 'false',
            ];

        }
        return $returnData;
    }

    public function nextCursorItemRackAddedById(string $rackId, string $nextCursorId): array
    {
        try {
            return Item::orderBy('id')->cursorPaginate(10, ['*'], 'cursor', $nextCursorId)->toArray();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return [];
        }
    }


    public function getItemNotYetAddedRackCursor(): array
    {
        try {
            return Item::whereNull('racks_id')->orderBy('id')->cursorPaginate(10)->toArray();
        } catch (\Exception $exception) {
            return [];
            Log::error($exception->getMessage());
        }
    }

    public function addNewRack(string $areaId): ?Rack
    {

        try {
            return Rack::create([
                'areas_id' => $areaId,
                'name' => '',
                'category_inventory' => ''
            ]);


        } catch (\Exception $e) {

            Log::error($e);
            return null;
        }


    }

    public function addNewArea(string $warehouseId): ?Area
    {
        try {

            return Area::create([
                'warehouses_id' => $warehouseId,
                'name' => '',
                'name' => '',
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }


    private function getItemRackAddedById(string $id)
    {
        try {
            return Item::where('racks_id', $id)
                ->orWhereNull('racks_id')
                ->orderBy('id')
                ->cursorPaginate(10)
                ->toArray();
        } catch (\Exception $exception) {
            return [];
            Log::error($exception->getMessage());
        }
    }

    private function nextCursorItemDataSource(string $rackId, string $nextCursorId): array
    {
        try {
            return Item::where('racks_id', $rackId)
                ->orWhereNull('racks_id')
                ->orderBy('id')
                ->cursorPaginate(10, ['*'], 'cursor', $nextCursorId)
                ->toArray();
        } catch (\Exception $exception) {
            return [];
            Log::error($exception->getMessage());
        }
    }
}
