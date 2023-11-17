<?php

namespace App\Service\Impl;

use App\Models\Area;
use App\Models\Item;
use App\Models\Rack;
use App\Models\Warehouse;
use App\Service\WarehouseService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseServiceImpl implements WarehouseService
{

    /** dapatkan data warehouse berdasarkan id
     * @param string $id
     * @return Warehouse
     * @throws Exception
     */
    public function getWarehouseById(string $id): Warehouse
    {

        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            $callStack = debug_backtrace();

            // Ambil fungsi yang memanggil method ini
            $callingFunction = $callStack[1]['function'];
            throw new Exception("detail warehouse tidak ditemukan karena data null $callingFunction", code: 1);
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
     * @throws Exception
     */
    public function getDetailDataAreaRackItemWarehouse(?Warehouse $warehouse): array
    {
        $areas = [];

        if (null) {
            $callStack = debug_backtrace();

            // Ambil fungsi yang memanggil method ini
            $callingFunction = $callStack[1]['function'];

            throw new Exception("data warehouse dari $callingFunction nullable", code: 2);
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
        } catch (Exception $exception) {
            return [];
            Log::error($exception->getMessage());
        }
    }

    public function getItemRackAddedByIdWithCursor(string $id): array
    {
        try {
            return Item::where('racks_id', $id)->orWhereNull('racks_id')->orderBy('id')->cursorPaginate(10)->toArray();
        } catch (Exception $exception) {
            return [];
            Log::error($exception->getMessage());
        }

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
            return Item::where('racks_id', $id)->orderBy('id')->cursorPaginate(10)->toArray();
        } catch (Exception $exception) {
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
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return [];
        }
    }


    public function getItemNotYetAddedRackCursor(): array
    {
        try {
            return Item::whereNull('racks_id')->orderBy('id')->cursorPaginate(10)->toArray();
        } catch (Exception $exception) {
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


        } catch (Exception $e) {

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

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    /**
     * fungsi untuk menyimpan data edit warehouse ke database
     * @param array $areas
     * @return bool
     * @throws Exception
     */
    public function saveWarehouse(array $areas, string $warehouseId, string $warehouseName, string $warehouseAddress): bool
    {

        if (empty($areas))
            throw new Exception('Gagal melakukan update warehouse parameter kosong');


        $areaContainer = [];
        $rackContainer = [];

        try {
            foreach ($areas as $areaKey => $area) {

                $area = $area['area'];
                $areaId = $area['id'] ?? null;
                $areaName = $area['area'] ?? null;
                $racks = $area['racks'] ?? null;

                if ($areaId == null || $areaName == null || $racks == null) {
                    throw new Exception('Gagal melakukan update warehouse data area kosong');
                }

                $areaContainer[] = $areaId;

                Log::debug($racks);

                $rackContainer[] = [
                    'area_id' => $areaId,
                ];

                foreach ($racks as $rack) {
                    $rackContainer[$areaKey]['rack'][] = $rack['id'];
                }


                // update data area
                $resultUpdateArea = $this->updateArea($areaId, $areaName);
                // update racks
                $resultUpdateRacks = $this->updateRacks($areaId, $racks);

            }

            // delete area berdasarkan id area yang tidak ada di area container
            $this->deleteAreaAndRack($areaContainer, $rackContainer);

            // update warehouse name dan address
            $this->updateWarehouseNameAndAddress($warehouseId, $warehouseName, $warehouseAddress);

            DB::commit();

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return true;
    }

    /**
     * lakukan update area saat fungsi save warehouse dipanggil
     * @throws Exception
     */
    private function updateArea(string $areaId, string $areaName)
    {

        // jika parameternya kosong maka lempar exception
        if (empty($areaId) || empty($areaName)) {
            throw new Exception('Gagal memperbarui area, parameter kosong');
        }

        try {

            $area = Area::findOrFail($areaId);

            if ($area->name !== $areaName) {

                $area->update(['name' => $areaName]);
                Log::debug('save');

            }

        } catch (Exception $e) {
            Log::error('Gagal memperbarui area. Exception: ' . $e->getMessage());
        }
    }

    /**
     * fungsi ini berguna untuk melakukan update nama rack berdasarkan area
     * saat dilakukan proses penyimpanan edit area warehouse
     * @param string $areaId
     * @param array $updateRacks
     * @return bool|null
     */
    private function updateRacks(string $areaId, array $updateRacks)
    {

        // jika paramater kosong maka lempar expcetion
        if (empty($areaId) || empty($updateRacks)) throw new Exception('Gagal memperbarui rack, parameter kosong');


        /**
         * Lakukan perbandingan di rack terbaru dengan rack lama
         * apakah ada perbedaan data
         */
        try {

            // cari rak berdasarkan area id
            $racks = Rack::where('areas_id', $areaId)->get();


            if ($racks != null) {
                foreach ($racks as $rack) {
                    $rackId = $rack['id'];
                    $rackName = $rack['name'];
                    $categoryInv = $rack['category_inventory'];


                    Log::debug('data racks');
                    Log::debug($rack);
                    Log::debug($rackId);

                    foreach ($updateRacks as $updateRack) {
                        if ($updateRack['id'] == $rackId) {
                            if ($updateRack['name'] != $rackName) {
                                Log::debug('rack name tidak sama');
                                $rack->update(['name' => $updateRack['name']]);
                            }

                            if ($updateRack['category_inventory'] != $categoryInv) {
                                Log::debug('cat inv tidak sama');
                                $rack->update(['category_inventory' => $updateRack['category_inventory']]);
                            }
                        }


                    }

                }


            }


        } catch (Exception $e) {

            Log::error($e->getMessage());

        }
    }

    public function deleteAreaAndRack(array $areaContainer, array $rackContainer)
    {
        try {
            // jika paramater kosong maka lempar expcetion
            if (empty($areaContainer) || empty($rackContainer)) throw new Exception('Gagal mengahapus area dan rack, karena parameter kosong');

            // delete rack dan item dari penghapusan 1 area penuh
            Rack::whereNotIn('areas_id', $areaContainer)
                ->get()
                ->each(function ($rack) {
                    $rack->item()->delete();
                    $rack->delete();
                });

            // delete area
            Area::whereNotIn('id', $areaContainer)->delete();


            // delete rack
            foreach ($rackContainer as $rack) {


                if (isset($rack['area_id']) && isset($rack['rack'])) {
                    $areaId = $rack['area_id'];
                    $rack = $rack['rack'];

                    Rack::where('areas_id', $areaId)
                        ->whereNotIn('id', $rack)
                        ->get()
                        ->each(function ($rack) {
                            // Hapus item terkait pada rak
                            $rack->item()->delete();
                            // Hapus rak itu sendiri
                            $rack->delete();
                        });

                }

            }


        } catch (Exception $e) {

            Log::error($e->getMessage());
        }
    }

    private function updateWarehouseNameAndAddress(string $warehouseId, string $warehouseName, string $warehouseAddress)
    {
        try {
            $warehouse = Warehouse::findOrFail($warehouseId);

            if ($warehouseName != null) {
                $warehouse->update(['name' => $warehouseName]);
            }

            if ($warehouseAddress != null) {
                $warehouse->update(['address' => $warehouseAddress]);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
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
        } catch (Exception $exception) {
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
        } catch (Exception $exception) {
            return [];
            Log::error($exception->getMessage());
        }
    }
}
