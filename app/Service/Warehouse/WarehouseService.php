<?php

namespace App\Service\Warehouse;

use App\Contract\Warehouse\WarehouseRepository;
use App\Dto\WarehouseDTO;
use App\Dto\WarehouseEnum;
use App\Models\Warehouse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseService implements \App\Contract\Warehouse\WarehouseService
{

    private WarehouseRepository $repository;

    /**
     * @param WarehouseRepository $repository
     */
    public function __construct(WarehouseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * buat warehouse baru untuk outlet atau central kitchen berdasarkan enum warehouse dto
     * @return void
     */
    public function create(?WarehouseDTO $warehouseDTO): Warehouse
    {

        Log::info(json_encode([
            'message' => 'Membuat warehouse baru',
            'user' => 'user {user}'
        ]));


        // jika warehouse dto null
        if (is_null($warehouseDTO)) {
            report(new Exception('gagal membuat warehouse dikarenakan warehouse dto null'));
            abort(400);
        }


        try {
            $resultCreateWarehouse = null;

            DB::transaction(function () use ($warehouseDTO, &$resultCreateWarehouse) {
                $resultCreateWarehouse = $this->createWarehouse($warehouseDTO);

                // set id warehouse ke warehouse dto
                $warehouseDTO->setId($resultCreateWarehouse->id);

                // buat area dan rack
                $this->createAreaAndRack($warehouseDTO);

                // cek tipe warehouse yang dibuat
                $type = $this->getCreateType($warehouseDTO);

                if ($type == WarehouseEnum::CENTRAL) {
                    $this->createWarehouseCentral($resultCreateWarehouse, $warehouseDTO);
                } else {
                    $this->createWarehouseOutlet($resultCreateWarehouse, $warehouseDTO);
                }
            });

            Log::info(json_encode([
                'message' => 'Berhasil membuat warehouse baru',
                'user' => '{user}',
                'warehouseDTO' => $warehouseDTO,
                'type' => $warehouseDTO->getEnum()->name
            ]));


            return $resultCreateWarehouse;
        } catch (Exception $exception) {
            Log::error(json_encode([
                'error' => 'gagal membuat warehouse baru',
                'warehouseDTO' => $warehouseDTO,
                'user' => '{user}',
            ]));
            report($exception);
            abort(400);
        }

    }

    public function createWarehouse(WarehouseDTO $warehouseDTO): Warehouse
    {
        return $this->repository->createWarehouse($warehouseDTO);
    }

    public function createAreaAndRack(WarehouseDTO $warehouseDTO): bool
    {
//        format
//        $data = [
//            [
//                'area' => 'Area a',
//                'rack' => [
//                    'A1',
//                    'A2'
//                ]
//            ],
//            [
//                'area' => 'Area b',
//                'rack' => [
//                    'B1'
//                ]
//            ]
//        ];

        Log::info('Membuat area dan rack');
        $warehouseId = $warehouseDTO->getId();

        foreach ($warehouseDTO->getAreasAndRack() as $areaAndRacks) {
            // buat area pertama dulu
            $areaName = $areaAndRacks['area'];
            $area = $this->repository->createAreas($warehouseId, $areaName);

            // buat rack setelah area dibuat
            // rack dalam bentuk array pastikan dilooping
            foreach ($areaAndRacks['racks'] as $rackName) {
                $this->repository->createRacks($area->id, $rackName);
            }
        }

        return true;
    }

    public function getCreateType(WarehouseDTO $warehouseDTO): WarehouseEnum
    {
        return $warehouseDTO->getEnum();
    }

    public function createWarehouseCentral(Warehouse $warehouse, WarehouseDTO $warehouseDTO): array
    {
        return $this->repository->createRelationCentral($warehouse, $warehouseDTO);
    }

    public function createWarehouseOutlet(Warehouse $warehouse, WarehouseDTO $warehouseDTO): array
    {
        return $this->repository->createRelationOutlet($warehouse, $warehouseDTO);
    }


}
