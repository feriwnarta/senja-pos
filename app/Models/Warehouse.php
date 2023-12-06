<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Warehouse extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $guarded = [];

    protected $keyType = 'string';

    public function item(): HasMany
    {
        return $this->hasMany(Item::class, 'warehouses_id');
    }

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class, 'warehouses_id');
    }

    public function outlet(): BelongsToMany
    {
        return $this->belongsToMany(Outlet::class, 'warehouses_outlets', 'warehouses_id', 'outlets_id')->using(new class extends Pivot {
            use  HasUuids;
        })->withTimestamps();
    }

    public function centralKitchen(): BelongsToMany
    {
        return $this->belongsToMany(CentralKitchen::class, 'warehouses_central_kitchens', 'warehouses_id', 'central_kitchens_id')->using(new class extends Pivot {
            use  HasUuids;
        })->withTimestamps();
    }
}
