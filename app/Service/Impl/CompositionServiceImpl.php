<?php

namespace App\Service\Impl;

use App\Models\Category;
use App\Models\CentralKitchen;
use App\Models\Item;
use App\Models\Outlet;
use App\Models\StockItem;
use App\Models\Unit;
use App\Service\CompositionService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
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
            $outlet = Outlet::find($identifier);

            if ($outlet == null) {
                return null;
            }

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

    public function saveItem(string $route, string $routeProduce, string $inStock, string $minimumStock, $thumbnail, bool $isOutlet, ?string $placement, string $code, string $name, string $unit, string $description, string $category, string $url, string $avgCost, string $lastCost): string
    {


        try {
            DB::beginTransaction();

            $allowedRoutes = ['BUY', 'PRODUCE'];
            $allowedProduceRoutes = ['PRODUCEOUTLET', 'PRODUCECENTRALKITCHEN'];

            if (!in_array($route, $allowedRoutes)) {
                return 'Rute diluar yang ditentukan';
            }

            if ($route == 'PRODUCE' && !in_array($routeProduce, $allowedProduceRoutes)) {
                return 'Rute produksi diluar yang ditentukan';
            }

            $result = null;

            if ($thumbnail !== null) {
                $result = $thumbnail->store('public/item-image');
            }

            $item = Item::create([
                'code' => $code,
                'thumbnail' => $result,
                'racks_id' => $placement ?: null,
                'name' => $name,
                'description' => $description ?: null,
                'units_id' => $unit,
                'categories_id' => $category,
                'route' => ($route == 'BUY') ? $route : $routeProduce,
            ]);

            if ($isOutlet) {
                $item->outlet()->syncWithoutDetaching($url);
            } else {
                $item->centralKitchen()->syncWithoutDetaching($url);
            }

            DB::commit();

            if (!is_numeric($avgCost) || !is_numeric($lastCost) || intval($avgCost) < 0 || intval($lastCost) < 0) {
                Log::error('Gagal validasi nilai avg cost dan last cost sebagai numeric atau kurang dari 0 saat membuat item baru');
                return 'failed validate';
            }


            $cogsCalc = app()->make(CogsValuationCalc::class);

            $result = $cogsCalc->initialAvg(floatval($inStock), floatval($avgCost), floatval($lastCost));


            Log::debug($result);
            Log::debug('AVG COST => ' . $result['avg_cost']);


            $stock = StockItem::create([
                'items_id' => $item->id,
                'minimum_stock' => $minimumStock,
                'incoming_qty' => $result['incoming_qty'],
                'price_diff' => $result['price_diff'],
                'inventory_value' => $result['inventory_value'],
                'qty_on_hand' => $inStock,
                'avg_cost' => $result['avg_cost'],
                'last_cost' => $result['last_cost'],
            ]);

            Log::debug($item);
            Log::debug('save');
            Log::debug($stock);

            return 'success';

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('gagal membuat item baru');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return 'failed';
        }

    }

}
