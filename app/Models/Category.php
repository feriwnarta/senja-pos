<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasUuids;


    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code', 'name'];

    // Relasi many to many ke item model
//    public function items(): BelongsToMany
//    {
//        return $this->belongsToMany(Item::class, 'categories_items', 'categories_id', 'items_id')->using(new class extends Pivot {
//            use HasUuids;
//        })->withTimestamps();
//    }

//    public function units(): BelongsToMany
//    {
//        return $this->belongsToMany(Unit::class, 'categories_units', 'categories_id', 'units_id')->using(new class extends Pivot {
//            use  HasUuids;
//        })->withTimestamps();
//    }
}
