<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class CentralProduction extends Model
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

    public function centralKitchen(): BelongsTo
    {
        return $this->belongsTo(CentralKitchen::class, 'central_kitchens_id');
    }

    public function requestStock(): BelongsTo
    {
        return $this->belongsTo(RequestStock::class, 'request_stocks_id');
    }

    public function result(): HasMany
    {
        return $this->hasMany(CentralProductionResult::class, 'central_productions_id');
    }

    public function outbound(): HasMany
    {
        return $this->hasMany(WarehouseOutbound::class, 'central_productions_id');
    }

    public function remaining(): HasMany
    {
        return $this->hasMany(CentralProductionRemaining::class, 'central_productions_id');
    }


    public function shipping(): HasMany
    {
        return $this->hasMany(CentralProductionShipping::class, 'central_productions_id');
    }

    public function reference(): MorphMany
    {
        return $this->morphMany(WarehouseItemReceiptRef::class, 'receivable');
    }


}
