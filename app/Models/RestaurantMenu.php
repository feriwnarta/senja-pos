<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantMenu extends Model
{
    use HasUuids, HasFactory;

    protected $table = "restaurant_menus";
    protected $primaryKey = "id";
    protected $keyType = "string";

    protected $fillable = ['code', 'name', 'description', 'price', 'sku', 'thumbnail', 'code_category'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryMenu::class, "code_category", "code");
    }

    public function stockMenu(): BelongsTo
    {
        return $this->belongsTo(StockMenu::class, "menu_id", "id");
    }
}
