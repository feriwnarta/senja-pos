<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class RequestStock extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $table = 'request_stocks';
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

    public function requestStockDetail(): HasMany
    {
        return $this->hasMany(RequestStockDetail::class, 'request_stocks_id');
    }

    public function requestStockHistory(): HasMany
    {
        return $this->hasMany(RequestStockHistory::class, 'request_stocks_id');
    }

    public function centralProduction(): HasMany
    {
        return $this->hasMany(CentralProduction::class, 'request_stocks_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouses_id');
    }

    public function reference(): MorphMany
    {
        return $this->morphMany(PurchaseRequestRef::class, 'requestable');
    }

}
