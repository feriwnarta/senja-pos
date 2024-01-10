<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class WarehouseItemReceipt extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $userId = Auth::check() ? Auth::id() : 'USER NOT LOGIN';
            $model->created_by = $userId;
        });

        static::updating(function ($model) {
            $userId = Auth::check() ? Auth::id() : 'USER NOT LOGIN';
            $model->updated_by = $userId;
        });


    }

    public function reference(): MorphMany
    {
        return $this->morphMany(WarehouseItemReceiptRef::class, 'receivable');
    }

    public function details(): HasMany
    {
        return $this->hasMany(WarehouseItemReceiptDetail::class, 'warehouse_items_receipts_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouses_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(WarehouseItemReceiptHistory::class, 'warehouse_item_receipts_id');
    }

    public function itemReceiptRef(): BelongsTo
    {
        return $this->belongsTo(WarehouseItemReceiptRef::class, 'warehouse_item_receipt_refs_id');
    }
}
