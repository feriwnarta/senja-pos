<?php

namespace App\Service\Impl;

use App\Livewire\Composition\Item;
use App\Service\RecipeService;
use Exception;
use Illuminate\Support\Facades\Log;
use Ramsey\Collection\Collection;

class RecipeServiceImpl implements RecipeService
{

    public function getAllItem(): Collection
    {
        try {

            Item::all(['id', 'name']);

        } catch (Exception $exception) {
            Log::error('gagal mendapatkan all item');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }
}
