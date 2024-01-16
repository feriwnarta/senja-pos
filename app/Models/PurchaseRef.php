<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PurchaseRef extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];


    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    public function purchase(): HasMany
    {
        return $this->hasMany(Purchase::class, 'purchase_refs_id');
    }
}
