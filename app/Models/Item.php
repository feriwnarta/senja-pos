<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];


    public function racks(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

//    // Relasi many to many ke category
//    public function categories(): BelongsToMany
//    {
//        return $this->belongsToMany(Item::class, 'category_items', 'items_id', 'categories_id') > using(new class extends Pivot {
//                use HasUuids;
//            })->withTimestamps();
//    }
}
