<?php

use App\Livewire\PointOfSales\PosCategory;
use App\Livewire\PointOfSales\PosMenu;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('point-of-sales/menu', PosMenu::class);
Route::get('point-of-sales/category', PosCategory::class);

// Warehouse
Route::get("warehouse/list-warehouse", \App\Livewire\Warehouse\ListWarehouse::class);
Route::get("warehouse/list-warehouse/add-warehouse", \App\Livewire\Warehouse\AddWarehouse::class);
