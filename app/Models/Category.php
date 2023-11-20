<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Category extends Model
{
    use HasFactory, HasUuids;


    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code', 'name'];

    // Relasi many to many ke item model
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'categories_items', 'categories_id', 'items_id')->using(new class extends Pivot {
            use HasUuids;
        });
    }
}
