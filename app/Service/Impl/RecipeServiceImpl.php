<?php

namespace App\Service\Impl;

use App\Models\Item;
use App\Service\RecipeService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class RecipeServiceImpl implements RecipeService
{

    public function getAllItem(): ?Collection
    {
        try {

            return Item::all(['id', 'name']);

        } catch (Exception $exception) {
            Log::error('gagal mendapatkan all item');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return null;
        }
    }
}
