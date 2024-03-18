<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentralKitchenStock extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $guarded = [];

    public function centralKitchen(): BelongsTo
    {
        return $this->belongsTo(CentralKitchen::class, 'central_kitchens_id');
    }


    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'items_id');
    }
}
