<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class WarehouseOutbound extends Model
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

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouses_id');
    }


    public function production(): BelongsTo
    {
        return $this->belongsTo(CentralProduction::class, 'central_productions_id');
    }

    public function outboundItem(): HasMany
    {
        return $this->hasMany(WarehouseOutboundItem::class, 'warehouse_outbounds_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(WarehouseOutboundHistory::class, 'warehouse_outbounds_id');
    }


    public function receipt(): HasMany
    {
        return $this->hasMany(CentralKitchenReceipts::class, 'warehouse_outbounds_id');
    }
}
