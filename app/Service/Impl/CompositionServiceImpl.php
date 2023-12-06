<?php

namespace App\Service\Impl;

use App\Models\Category;
use App\Models\CentralKitchen;
use App\Models\Outlet;
use App\Models\Unit;
use App\Service\CompositionService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class CompositionServiceImpl implements CompositionService
{

    public function findOutletById(string $id): ?Outlet
    {
        try {
            return Outlet::find($id);
        } catch (Exception $exception) {
            Log::error('gagal mendapatkan data outlet berdasarkan id di composition service impl');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return null;
        }
    }

    public function findCentralKitchenById(string $id): ?CentralKitchen
    {
        try {
            return CentralKitchen::find($id);
        } catch (Exception $exception) {
            Log::error('gagal mendapatkan data central kitchen berdasarkan id di composition service impl');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return null;
        }
    }

    public function getAllUnit(): ?Collection
    {
        try {
            return Unit::all();
        } catch (Exception $exception) {
            Log::error('gagal mendapatkan data semua unit');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return null;
        }

    }

    public function getAllCategory(): ?Collection
    {
        try {
            return Category::all();
        } catch (Exception $exception) {
            Log::error('gagal mendapatkan data semua category');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return null;
        }

    }

    public function getPlacement(string $identifier, bool $isOutlet): ?array
    {
        try {
            $result = [];

            $warehouses = $this->getWarehouses($identifier, $isOutlet);

            foreach ($warehouses as $warehouse) {
                foreach ($warehouse->areas()->get() as $area) {
                    $result[] = $this->formatAreaData($area);
                }
            }

            return $result;

        } catch (ModelNotFoundException $notFoundException) {
            Log::error('Model not found');

        } catch (Exception $exception) {
            Log::error('Failed to get data for all categories');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

        return null;
    }

    private function getWarehouses(string $identifier, bool $isOutlet)
    {
        if ($isOutlet) {
            return Outlet::find($identifier)->warehouse()->get();
        }

        return CentralKitchen::find($identifier)->warehouse()->get();
    }

    private function formatAreaData($area)
    {
        return [
            'areaId' => $area->id,
            'areaName' => $area->name,
            'rack' => $area->racks()->get()->map(function ($rack) {
                return [
                    'rackId' => $rack->id,
                    'rackName' => $rack->name,
                ];
            })->all(),
        ];
    }
}
