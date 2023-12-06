<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code', 'name'];


//    public function categories(): BelongsToMany
//    {
//        return $this->belongsToMany(Category::class, 'categories_units', 'units_id', 'categories_id')->using(new class extends Pivot {
//            use  HasUuids;
//        })->withTimestamps();
//    }
}
