<?php

namespace App\Service\Impl;

use App\Models\Category;
use App\Models\Item;
use App\Service\CategoryItemService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryItemServiceImpl implements CategoryItemService
{

    public function getItemCursor(array $id): array
    {

        return Item::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('categories_items')
                ->whereRaw('categories_items.items_id = items.id');
        })
            ->orderBy('id')
            ->cursorPaginate(10)
            ->toArray();

    }

    public function getItemNextCursor(string $nextCursor): array
    {
        return Item::orderBy('id')->cursorPaginate(10, ['*'], 'cursor', $nextCursor)->toArray();
    }


    public function saveCategory(string $code, string $name, string $unit, array $items): Category
    {
        try {
            DB::beginTransaction();

            // buat category dan simpan
            $category = Category::create([
                'code' => $code,
                'name' => $name,
            ]);

            $category->items()->syncWithoutDetaching($items);
            $category->units()->attach($unit);
            DB::commit();

            return $category;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            throw $exception;
        }

    }
}
