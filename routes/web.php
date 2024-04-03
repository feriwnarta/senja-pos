<?php

use App\Livewire\CentralKitchen\AddCentralKitchen;
use App\Livewire\CentralKitchen\ListCentralKitchen;
use App\Livewire\CentralKitchen\ListRemainingProductionStock;
use App\Livewire\CentralKitchen\Production;
use App\Livewire\CentralKitchen\ProductionDetail;
use App\Livewire\CentralKitchen\ViewCentralKitchen;
use App\Livewire\Composition\CreateItem;
use App\Livewire\Composition\CreateRecipe;
use App\Livewire\Composition\Item;
use App\Livewire\Composition\Recipe;
use App\Livewire\Composition\ViewCategory;
use App\Livewire\Composition\ViewItem;
use App\Livewire\Composition\ViewRecipeItem;
use App\Livewire\Composition\ViewUnit;
use App\Livewire\Dashboard\Summary;
use App\Livewire\Outlet\AddOutlet;
use App\Livewire\Outlet\ListOutlet;
use App\Livewire\Purchase\CreateSupplier;
use App\Livewire\Purchase\PurchasedDetail;
use App\Livewire\Purchase\PurchaseDetail;
use App\Livewire\Purchase\PurchaseRequestDetail;
use App\Livewire\Purchase\Purchasing;
use App\Livewire\Purchase\Supplier;
use App\Livewire\Purchasing\PurchasingDetail;
use App\Livewire\User\CreateUser;
use App\Livewire\User\Pemission;
use App\Livewire\User\Permission;
use App\Livewire\User\User;
use App\Livewire\Warehouse\AddCategory;
use App\Livewire\Warehouse\AddUnit;
use App\Livewire\Warehouse\AddWarehouse;
use App\Livewire\Warehouse\CategoryItem;
use App\Livewire\Warehouse\CreateTransaction;
use App\Livewire\Warehouse\DetailCategoryItem;
use App\Livewire\Warehouse\DetailWarehouse;
use App\Livewire\Warehouse\EditRequestStock;
use App\Livewire\Warehouse\ListStock;
use App\Livewire\Warehouse\ListWarehouse;
use App\Livewire\Warehouse\StockItem;
use App\Livewire\Warehouse\Transaction;
use App\Livewire\Warehouse\TransactionDetail;
use App\Livewire\Warehouse\TransactionDetailReceipt;
use App\Livewire\Warehouse\Unit;
use App\Livewire\Warehouse\ViewRequestStock;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/', function () {
    return redirect()->to('login');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('dashboard/summary', Summary::class)->name('summary');

    Route::get('composition/item', Item::class)->name('composition-item');
    Route::get('composition/item/view/{item}', ViewItem::class)->name('composition-view-item');
    Route::get('composition/item/create-item', CreateItem::class)->name('composition-create-item');

    Route::get('composition/category-item', CategoryItem::class)->name('category-item');
    Route::get('composition/category-item/view/{category}', ViewCategory::class)->name('category-view-item');
    Route::get('composition/category-item/add-category', AddCategory::class)->name('add-category');
    Route::get('composition/category-item/detail-category', DetailCategoryItem::class)->name('detail-category');


    Route::get('composition/unit', Unit::class)->name('unit');
    Route::get('composition/unit/add-unit', AddUnit::class)->name('add-unit');
    Route::get('composition/unit/view/{unit}', ViewUnit::class)->name('view-unit');
    Route::get('composition/recipe', Recipe::class)->name('recipe');
    Route::get('composition/recipe/view/{recipeItem}', ViewRecipeItem::class)->name('view-recipe');
    Route::get('composition/recipe/create-recipe', CreateRecipe::class)->name('create-recipe');

// Warehouse
    Route::get('warehouse/stock', StockItem::class)->name('stock');
    Route::get('warehouse/list-warehouse', ListWarehouse::class)->name('list-warehouse');
    Route::get('warehouse/list-warehouse/add-warehouse', AddWarehouse::class)->name('add-warehouse');
    Route::get('warehouse/list-warehouse/view/{warehouse}', DetailWarehouse::class)->name('detail-warehouse');

    Route::get('warehouse/transaction', Transaction::class)->name('warehouse-transaction');
    Route::get('warehouse/stock', ListStock::class)->name('warehouse-list-stock');
    Route::get('/warehouse/transaction/request-stock/view/{requestStock}', ViewRequestStock::class)->name('view-request-stock');
    Route::get('/warehouse/transaction/request-stock/edit/{requestStock}', EditRequestStock::class)->name('edit-request-stock');
    Route::get('warehouse/transaction/add-transaction', CreateTransaction::class)->name('warehouse-add-transaction');

    Route::get('warehouse/transaction/detail-out/', TransactionDetail::class)->name('warehouse-transaction-detail');
    Route::get('warehouse/transaction/detail-receipt/', TransactionDetailReceipt::class)->name('warehouse-transaction-detail-receipt');

    Route::get('central-kitchen/list-central-kitchen', ListCentralKitchen::class)->name('list-central-kitchen');
    Route::get('central-kitchen/remaining-production-stock', ListRemainingProductionStock::class)->name('remaining-production-stock');
    Route::get('central-kitchen/list-central-kitchen/view/{centralKitchen}', ViewCentralKitchen::class)->name('view-central-kitchen');
    Route::get('central-kitchen/list-central-kitchen/add-central-kitchen', AddCentralKitchen::class)->name('add-central-kitchen');
    Route::get('central-kitchen/production', Production::class)->name('central-kitchen-production');
    Route::get('central-kitchen/production/detail-production', ProductionDetail::class)->name('central-kitchen-production-detail');

    Route::get('outlet/list-outlet', ListOutlet::class)->name('list-outlet');
    Route::get('outlet/list-outlet/add-outlet', AddOutlet::class)->name('add-outlet');

    Route::get('supplier', Supplier::class)->name('supplier');
    Route::get('supplier/create-supplier', CreateSupplier::class)->name('create-supplier');
    Route::get('purchase', Purchasing::class)->name('purchase');
    Route::get('purchase-request/detail', PurchaseRequestDetail::class)->name('purchase-request-detail');
    Route::get('purchased/detail', PurchasedDetail::class)->name('purchased-detail');
    Route::get('purchase/detail', PurchaseDetail::class)->name('purchase-detail');

    Route::get('user', User::class)->name('user');
    Route::get('user/create-user', CreateUser::class)->name('create-user');
    Route::get('user/permission', Permission::class)->name('permission');

});

Route::get('optimize', function () {
    Artisan::call('optimize');
//    Artisan::call('storage:link');
});
