<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WarehouseShippingRef extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function shipping(): HasOne {
        return $this->hasOne(WarehouseShipping::class, 'warehouse_shipping_refs_id');
    }

    public function shippable(): MorphTo
    {
        return $this->morphTo();
    }
}
