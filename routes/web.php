<?php

use App\Http\Controllers\Warehouse\WarehouseController;
use App\Livewire\CentralKitchen\AddCentralKitchen;
use App\Livewire\CentralKitchen\ListCentralKitchen;
use App\Livewire\Composition\CreateItem;
use App\Livewire\Composition\Item;
use App\Livewire\Outlet\AddOutlet;
use App\Livewire\Outlet\ListOutlet;
use App\Livewire\Warehouse\AddCategory;
use App\Livewire\Warehouse\AddUnit;
use App\Livewire\Warehouse\AddWarehouse;
use App\Livewire\Warehouse\CategoryItem;
use App\Livewire\Warehouse\DetailCategoryItem;
use App\Livewire\Warehouse\DetailUnit;
use App\Livewire\Warehouse\DetailWarehouse;
use App\Livewire\Warehouse\ListWarehouse;
use App\Livewire\Warehouse\StockItem;
use App\Livewire\Warehouse\Unit;
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


Route::get('composition/item', Item::class)->name('composition-item');
Route::get('composition/item/create-item', CreateItem::class)->name('composition-create-item');

Route::get("composition/category-item", CategoryItem::class)->name('category-item');
Route::get("composition/category-item/add-category", AddCategory::class)->name('add-category');
Route::get("composition/category-item/detail-category", DetailCategoryItem::class)->name('detail-category');


Route::get("composition/unit", Unit::class)->name('unit');
Route::get("composition/unit/add-unit", AddUnit::class)->name('add-unit');
Route::get("composition/unit/detail-unit", DetailUnit::class)->name('detail-unit');

// Warehouse

Route::controller(WarehouseController::class)->group(function () {
    Route::get("warehouse/stock", StockItem::class)->name('stock');

    Route::get("warehouse/list-warehouse", ListWarehouse::class)->name('list-warehouse');
    Route::get("warehouse/list-warehouse/add-warehouse", AddWarehouse::class)->name('add-warehouse');
    Route::get("warehouse/list-warehouse/detail-warehouse", DetailWarehouse::class)->name('detail-warehouse');

});

Route::get('central-kitchen/list-central-kitchen', ListCentralKitchen::class)->name('list-central-kitchen');
Route::get('central-kitchen/list-central-kitchen/add-central-kitchen', AddCentralKitchen::class)->name('add-central-kitchen');

Route::get('outlet/list-outlet', ListOutlet::class)->name('list-outlet');
Route::get('outlet/list-outlet/add-outlet', AddOutlet::class)->name('add-outlet');

