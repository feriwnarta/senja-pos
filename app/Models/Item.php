<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Item extends Model
{
    use HasFactory, HasUuids;

    const BUY = 'BUY';
    const PRODUCE = 'PRODUCE';
    const SELF_PRODUCE = 'OUTLETPRODUCE';
    public $incrementing = false;

    // Nilai-nilai valid untuk kolom 'route'
    protected $keyType = 'string';
    protected $guarded = [];


//    public function racks(): BelongsTo
//    {
//        return $this->belongsTo(Rack::class, 'racks_id');
//    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'units_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function stockItem(): HasMany
    {
        return $this->hasMany(StockItem::class, 'items_id');
    }

    public function placement(): HasMany
    {
        return $this->hasMany(ItemPlacement::class, 'items_id');
    }

    public function recipeDetail(): HasMany
    {
        return $this->hasMany(RecipeItemDetail::class, 'items_id');
    }

    public function recipe(): HasMany
    {
        return $this->hasMany(RecipeItem::class, 'items_id');
    }

    public function outlet(): BelongsToMany
    {
        return $this->belongsToMany(Outlet::class, 'items_outlets', 'items_id', 'outlets_id')->using(new class extends Pivot {
            use  HasUuids;
        })->withTimestamps();
    }

    public function centralKitchen(): BelongsToMany
    {
        return $this->belongsToMany(CentralKitchen::class, 'items_central_kitchens', 'items_id', 'central_kitchens_id')->using(new class extends Pivot {
            use  HasUuids;
        })->withTimestamps();
    }



//    // Relasi many to many ke category
//    public function categories(): BelongsToMany
//    {
//        return $this->belongsToMany(Item::class, 'category_items', 'items_id', 'categories_id') > using(new class extends Pivot {
//                use HasUuids;
//            })->withTimestamps();
//    }
}
