<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class PurchaseDetail extends Model
{
    use HasFactory, HasUuids, HasEagerLimit;

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

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchases_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(PurchaseDetail::class, 'purchases_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'items_id');
    }
}
