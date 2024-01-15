<?php

namespace App\Service\Impl;

use App\Models\Item;
use App\Models\RecipeItem;
use App\Service\RecipeService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecipeServiceImpl implements RecipeService
{

    public function getAllItem(): ?Collection
    {
        try {

            return Item::with('warehouseItem.stockItem')->get();

        } catch (Exception $exception) {
            Log::error('gagal mendapatkan all item');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return null;
        }
    }

    // dapatkan bahan berdasarkan is menu, jika true maka dapatkan data data menu,
    public function selectMenuOrMaterial(bool $isMenu): ?Collection
    {
        try {

            // jika is menu tidak true maka ambilkan bahan yang dapat diproduksi untuk keperluan produksi setengah jadi
            if (!$isMenu) {
                $items = Item::whereIn('route', ['PRODUCECENTRALKITCHEN', 'PRODUCEOUTLET'])->select('id', 'name')->get();
                Log::debug("data item yang dipilih $items");
                return $items;
            }


            //TODO:  lakukan pencarian menu
            return null;

        } catch (Exception $exception) {
            Log::error('gagal mendapatkan menu atau bahan yang digunakan untuk membuat resep menu atau item');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return null;
        }

    }

    public function saveRecipeItem(string $code, string $itemsId, array $recipes): bool
    {
        try {
            DB::beginTransaction();
            $recipe = RecipeItem::create(['code' => $code, 'items_id' => $itemsId]);
            $recipe->recipeDetail()->createMany($recipes);
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('gagal menyimpan resep item');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return false;
        }
    }
}
