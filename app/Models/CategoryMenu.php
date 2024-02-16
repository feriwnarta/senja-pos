<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryMenu extends Model
{
    use HasUuids, HasFactory;

    protected $table = "category_menus";
    protected $primaryKey = "id";

    protected $fillable = ['code', 'name'];

    public function RestauransMenus(): HasMany
    {
        return $this->hasMany(RestaurantMenu::class, "code_category", "code");
    }
}
