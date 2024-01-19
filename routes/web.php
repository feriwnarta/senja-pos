<?php

use App\Livewire\CentralKitchen\AddCentralKitchen;
use App\Livewire\CentralKitchen\ListCentralKitchen;
use App\Livewire\CentralKitchen\Production;
use App\Livewire\CentralKitchen\ProductionDetail;
use App\Livewire\Composition\CreateItem;
use App\Livewire\Composition\CreateRecipe;
use App\Livewire\Composition\Item;
use App\Livewire\Composition\Recipe;
use App\Livewire\Outlet\AddOutlet;
use App\Livewire\Outlet\ListOutlet;
use App\Livewire\PointOfSalesKasir\Page\AktifOrderPos;
use App\Livewire\PointOfSalesKasir\Page\MenuOrder;
use App\Livewire\PointOfSalesKasir\Page\MutasiPos;
use App\Livewire\PointOfSalesKasir\Page\RiwayatShiftDetail;
use App\Livewire\PointOfSalesKasir\Page\RiwayatShiftPos;
use App\Livewire\PointOfSalesKasir\Page\ShiftAktifDetail;
use App\Livewire\PointOfSalesKasir\Page\ShiftAktifPos;
use App\Livewire\Purchase\CreateSupplier;
use App\Livewire\Purchase\Supplier;
use App\Livewire\Warehouse\AddCategory;
use App\Livewire\Warehouse\AddUnit;
use App\Livewire\Warehouse\AddWarehouse;
use App\Livewire\Warehouse\CategoryItem;
use App\Livewire\Warehouse\CreateTransaction;
use App\Livewire\Warehouse\DetailCategoryItem;
use App\Livewire\Warehouse\DetailUnit;
use App\Livewire\Warehouse\DetailWarehouse;
use App\Livewire\Warehouse\ListWarehouse;
use App\Livewire\Warehouse\StockItem;
use App\Livewire\Warehouse\Transaction;
use App\Livewire\Warehouse\TransactionDetail;
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
Route::get("composition/recipe", Recipe::class)->name('recipe');
Route::get("composition/recipe/create-recipe", CreateRecipe::class)->name('create-recipe');

// Warehouse
Route::get("warehouse/stock", StockItem::class)->name('stock');
Route::get("warehouse/list-warehouse", ListWarehouse::class)->name('list-warehouse');
Route::get("warehouse/list-warehouse/add-warehouse", AddWarehouse::class)->name('add-warehouse');
Route::get("warehouse/list-warehouse/detail-warehouse", DetailWarehouse::class)->name('detail-warehouse');
Route::get("warehouse/transaction", Transaction::class)->name('warehouse-transaction');
Route::get("warehouse/transaction/add-transaction", CreateTransaction::class)->name('warehouse-add-transaction');

Route::get('warehouse/transaction/detail/', TransactionDetail::class)->name('warehouse-transaction-detail');

Route::get('central-kitchen/list-central-kitchen', ListCentralKitchen::class)->name('list-central-kitchen');
Route::get('central-kitchen/list-central-kitchen/add-central-kitchen', AddCentralKitchen::class)->name('add-central-kitchen');
Route::get('central-kitchen/production', Production::class)->name('central-kitchen-production');
Route::get('central-kitchen/production/detail-production', ProductionDetail::class)->name('central-kitchen-production-detail');

Route::get('outlet/list-outlet', ListOutlet::class)->name('list-outlet');
Route::get('outlet/list-outlet/add-outlet', AddOutlet::class)->name('add-outlet');

Route::get('supplier', Supplier::class)->name('supplier');
Route::get('supplier/create-supplier', CreateSupplier::class)->name('create-supplier');

// Point Of Sales Kasir
Route::get('/pos/menu', MenuOrder::class)->name('menu');
Route::get('/pos/active-order', AktifOrderPos::class)->name('active-order');
Route::get('/pos/active-shift', ShiftAktifPos::class)->name('active-shift');
Route::get('/pos/active-shift-detail', ShiftAktifDetail::class)->name('active-shift-detail');
Route::get('/pos/riwayat-shift', RiwayatShiftPos::class)->name('riwayat-shift');
Route::get('/pos/riwayat-shift-detail', RiwayatShiftDetail::class)->name('riwayat-shift-detail');
Route::get('/pos/mutasi', MutasiPos::class)->name('mutasi');