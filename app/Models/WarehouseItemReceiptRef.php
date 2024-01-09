<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WarehouseItemReceiptRef extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function receivable(): MorphTo
    {
        return $this->morphTo();
    }
}
